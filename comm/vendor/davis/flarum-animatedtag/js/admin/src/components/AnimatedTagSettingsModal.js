import SettingsModal from 'flarum/components/SettingsModal';

export default class AnimatedTagSettingsModal extends SettingsModal {
  className() {
    return 'AnimatedTagSettingsModal Modal--small';
  }

  title() {
    return 'Animated Tag Settings';
  }

  form() {
    return [
      <div className="Form-group">
        <label>{app.translator.trans('davis-animatedtag.admin.animationtype')}</label>
        <select className="FormControl" bidi={this.setting('davis.animatedtag.animationtype')}>
          <option value="0">{app.translator.trans('davis-animatedtag.admin.animationtypes.0')}</option>
          <option value="1">{app.translator.trans('davis-animatedtag.admin.animationtypes.1')}</option>
          <option value="2">{app.translator.trans('davis-animatedtag.admin.animationtypes.2')}</option>
          <option value="3">{app.translator.trans('davis-animatedtag.admin.animationtypes.3')}</option>
        </select>
      </div>
    ];
  }
}
