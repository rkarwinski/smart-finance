import { createRouter, createWebHistory } from 'vue-router';
import DashboardPage from './pages/DashboardPage.vue';
import SavingsPage from './pages/SavingsPage.vue';
import ExpensesPage from './pages/ExpensesPage.vue';
import IncomesPage from './pages/IncomesPage.vue';

const routes = [
  { path: '/', name: 'Dashboard', component: DashboardPage },
  { path: '/savings', name: 'Savings', component: SavingsPage },
  { path: '/expenses', name: 'Expenses', component: ExpensesPage },
  { path: '/incomes', name: 'Incomes', component: IncomesPage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
