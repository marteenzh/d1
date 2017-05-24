/**
 * @file
 * Defines Javascript behaviors for the autosave_form module.
 */

(function ($, Drupal, drupalSettings) {

  'use strict';

  /**
   * Define defaults.
   */
  Drupal.autosaveForm = {
      timer: null,
      interval: null,
      autosaveFormRunning: false,
      autosaveFormInstances: {},
      initialized: false,
      message: '',
      dialog_options: [],
      autosave_submit_class: 'autosave-form-save',
      autosave_restore_class: 'autosave-form-restore',
      autosave_reject_class: 'autosave-form-reject'
  };

  /**
   * Behaviors the autosave form feature.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches the autosave behavior.
   */
  Drupal.behaviors.autosaveForm = {
    attach: function (context, settings) {

      if (settings.hasOwnProperty('autosaveForm')) {
        $.extend(Drupal.autosaveForm, settings.autosaveForm)
      }

      if (Drupal.autosaveForm.initialized) {
        // If requested so turn off ajax submission.
        if (!Drupal.autosaveForm.autosaveFormRunning && Drupal.autosaveForm.timer) {
          clearInterval(Drupal.autosaveForm.timer);
          Drupal.autosaveForm.timer = null;
        }
        else {
          return;
        }
      }

      if (!Drupal.autosaveForm.initialized && !Drupal.autosaveForm.autosaveFormRunning) {
        Drupal.autosaveForm.initialized = true;

        if (Drupal.autosaveForm.message) {
          var dialogOptions = {
            open: function() {
              $(this).siblings('.ui-dialog-titlebar').remove();
            },
            modal: true,
            zIndex: 10000,
            position: { my: 'top', at: 'top+25%' },
            autoOpen: true,
            width: 'auto',
            resizable: false,
            closeOnEscape: false,
            buttons: {
              'button_confirm': {
                text: Drupal.t('Resume editing'),
                'class': 'autosave-form-resume-button',
                click: function () {
                  // Non ajax buttons are bound to click.
                  $('.' + Drupal.autosaveForm.autosave_restore_class).click();
                }
              },
              'button_reject': {
                text: Drupal.t('Discard'),
                'class': 'autosave-form-reject-button',
                click: function () {
                  triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_reject_class);
                  $(this).dialog('close');
                },
                primary: true
              }
            },
            close: function (event, ui) {
              $(this).remove();
              $(context).find('.' + Drupal.autosaveForm.autosave_restore_class).remove();
              $(context).find('.' + Drupal.autosaveForm.autosave_reject_class).remove();
              autosavePeriodic();
            }
          };

          $.extend(true, dialogOptions, Drupal.autosaveForm.dialog_options);

          $('<div></div>').appendTo('body')
            .html('<div>' + Drupal.autosaveForm.message + '</div>')
            .dialog(dialogOptions);
        }
        else {
          autosavePeriodic();
        }
      }

      /**
       * Returns the ajax instance corresponding to an element.
       *
       * @param class_name
       *   The element class name for which to return its ajax instance.
       *
       * @returns {Drupal.Ajax | null}
       *   The ajax instance if found, otherwise null.
       */
      function findAjaxInstance(class_name) {
        if (!Drupal.autosaveForm.autosaveFormInstances.hasOwnProperty(class_name)) {
          var element = document.getElementsByClassName(class_name)[0];
          var ajax = null;
          var selector = '#' + element.id;
          for (var index in Drupal.ajax.instances) {
            var ajaxInstance = Drupal.ajax.instances[index];
            if (ajaxInstance && (ajaxInstance.selector == selector)) {
              ajax = ajaxInstance;
              break;
            }
          }
          Drupal.autosaveForm.autosaveFormInstances[class_name] = ajax;
        }
        return Drupal.autosaveForm.autosaveFormInstances[class_name];
      }

      /**
       * Triggers an ajax submit based on the class of the ajax element.
       *
       * @param ajax_class
       *   The class of the ajax element.
       */
      function triggerAjaxSubmitWithoutProgressIndication(ajax_class) {
        var ajax = findAjaxInstance(ajax_class);
        if (ajax) {
          // Disable progress indication.
          ajax.progress = false;
          $(ajax.element).trigger(ajax.element_settings.event);
        }
      }

      /**
       * Starts periodic autosave submission.
       */
      function autosavePeriodic() {
        if (Drupal.autosaveForm.interval) {
          Drupal.autosaveForm.autosaveFormRunning = true;

          // Run the autosave submission at the beginning to capture the user
          // input and compare it later for changes, however wait for sometime
          // until triggering the submission in order to let all the Drupal
          // behaviors be executed and probably alter the page before doing the
          // first submission, otherwise we might capture not the correct user
          // input and on the second submission detect changes even if there
          // aren't actually any changes.
          // @todo Remove this and let autosave attach itself instead as the
          // last behavior as soon as the following issues are fixed:
          // @see https://www.drupal.org/node/2367655
          // @see https://www.drupal.org/node/2474019
          if (Drupal.autosaveForm.interval > 500) {
            setTimeout(function() {triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_submit_class);}, 500);
          }

          Drupal.autosaveForm.timer = setInterval(function () {triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_submit_class);}, Drupal.autosaveForm.interval);
        }
      }

    }
  };

})(jQuery, Drupal, drupalSettings);
