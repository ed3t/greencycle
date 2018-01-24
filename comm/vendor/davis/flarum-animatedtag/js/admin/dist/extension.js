System.register('davis/animatedtag/components/AnimatedTagSettingsModal', ['flarum/components/SettingsModal'], function (_export) {
  'use strict';

  var SettingsModal, AnimatedTagSettingsModal;
  return {
    setters: [function (_flarumComponentsSettingsModal) {
      SettingsModal = _flarumComponentsSettingsModal['default'];
    }],
    execute: function () {
      AnimatedTagSettingsModal = (function (_SettingsModal) {
        babelHelpers.inherits(AnimatedTagSettingsModal, _SettingsModal);

        function AnimatedTagSettingsModal() {
          babelHelpers.classCallCheck(this, AnimatedTagSettingsModal);
          babelHelpers.get(Object.getPrototypeOf(AnimatedTagSettingsModal.prototype), 'constructor', this).apply(this, arguments);
        }

        babelHelpers.createClass(AnimatedTagSettingsModal, [{
          key: 'className',
          value: function className() {
            return 'AnimatedTagSettingsModal Modal--small';
          }
        }, {
          key: 'title',
          value: function title() {
            return 'Animated Tag Settings';
          }
        }, {
          key: 'form',
          value: function form() {
            return [m(
              'div',
              { className: 'Form-group' },
              m(
                'label',
                null,
                app.translator.trans('davis-animatedtag.admin.animationtype')
              ),
              m(
                'select',
                { className: 'FormControl', bidi: this.setting('davis.animatedtag.animationtype') },
                m(
                  'option',
                  { value: '0' },
                  app.translator.trans('davis-animatedtag.admin.animationtypes.0')
                ),
                m(
                  'option',
                  { value: '1' },
                  app.translator.trans('davis-animatedtag.admin.animationtypes.1')
                ),
                m(
                  'option',
                  { value: '2' },
                  app.translator.trans('davis-animatedtag.admin.animationtypes.2')
                ),
                m(
                  'option',
                  { value: '3' },
                  app.translator.trans('davis-animatedtag.admin.animationtypes.3')
                )
              )
            )];
          }
        }]);
        return AnimatedTagSettingsModal;
      })(SettingsModal);

      _export('default', AnimatedTagSettingsModal);
    }
  };
});;
System.register('davis/animatedtag/main', ['flarum/extend', 'flarum/app', 'davis/animatedtag/components/AnimatedTagSettingsModal'], function (_export) {
  'use strict';

  var extend, app, AnimatedTagSettingsModal;
  return {
    setters: [function (_flarumExtend) {
      extend = _flarumExtend.extend;
    }, function (_flarumApp) {
      app = _flarumApp['default'];
    }, function (_davisAnimatedtagComponentsAnimatedTagSettingsModal) {
      AnimatedTagSettingsModal = _davisAnimatedtagComponentsAnimatedTagSettingsModal['default'];
    }],
    execute: function () {

      app.initializers.add('davis-animatedtag', function (app) {
        app.extensionSettings['davis-animatedtag'] = function () {
          return app.modal.show(new AnimatedTagSettingsModal());
        };
      });
    }
  };
});