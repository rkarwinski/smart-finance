

import { createApp } from 'vue';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/aura-light-blue/theme.css';
import 'primeicons/primeicons.css';
import 'primeflex/primeflex.css';
import Chart from 'primevue/chart';

import App from './App.vue';
import Menubar from 'primevue/menubar';
import Button from 'primevue/button';
import Card from 'primevue/card';
import Dropdown from 'primevue/dropdown';
import InputText from 'primevue/inputtext';
import Paginator from 'primevue/paginator';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ToastService from 'primevue/toastservice';
import ToastPlugin from './plugins/toast';

// Necessário para gráficos do PrimeVue
import ChartJS from 'chart.js/auto';
window.Chart = ChartJS;

import router from './router';

const app = createApp(App);
app.use(PrimeVue);
app.use(router);
app.use(ToastService);
app.use(ToastPlugin);
app.component('Chart', Chart);
app.component('Menubar', Menubar);
app.component('Button', Button);
app.component('Card', Card);
app.component('Dropdown', Dropdown);
app.component('InputText', InputText);
app.component('Paginator', Paginator);
app.component('Dialog', Dialog);
app.component('Toast', Toast);
app.component('DataTable', DataTable);
app.component('Column', Column);
app.mount('#app');
