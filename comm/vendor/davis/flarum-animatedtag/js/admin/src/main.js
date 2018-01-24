import { extend } from 'flarum/extend';
import app from 'flarum/app';
import AnimatedTagSettingsModal from 'davis/animatedtag/components/AnimatedTagSettingsModal';

app.initializers.add('davis-animatedtag', app => {
  app.extensionSettings['davis-animatedtag'] = () => app.modal.show(new AnimatedTagSettingsModal());
});
