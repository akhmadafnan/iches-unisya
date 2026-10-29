import { createApp } from 'vue';
import './fixture.css';
import '@fontsource-variable/inter';
import '@fontsource-variable/sora';
import '@fontsource-variable/noto-sans-arabic';
import '@fontsource-variable/jetbrains-mono';
import '@fontsource-variable/geist-mono';
import Fixture from './Fixture.vue';

(window as unknown as { fixture: unknown }).fixture = createApp(Fixture).mount('#fixture');
