import Vue from 'vue/dist/vue.esm.js';
import VueRouter from 'vue-router';
import Award from './pages/award.vue'; 
import CreateAward from './pages/award-create.vue';
import EditAward from './pages/award-edit.vue';
import CategoriesPage from './pages/categories.vue';
import CategoriesEditPage from './pages/categories-edit.vue';
import EditAwardCategories from './pages/award-categories.vue';
import AwardStatus from './pages/award-status.vue';
import AwardStatistics from './pages/award-statistics.vue';
import FinalistsEditPage from './pages/finalists-edit.vue';
import WinnersEditPage from './pages/winners-edit.vue';
import APIService from './plugins/api-service.js';

Vue.use(VueRouter);
Vue.use(APIService);

let AwardsRouter = new VueRouter({
  routes: [
    { path: '/', component: Award },
    { path: '/create-award', component: CreateAward },
    { path: '/award/:id', component: EditAward, props: true },
    { path: '/award/:id/categories', component: EditAwardCategories, props: true },
    { path: '/award/:id/statistics', component: AwardStatistics, props: true },
    { path: '/award/:id/status', component: AwardStatus, props: true },
    { path: '/award/:id/finalists', component: FinalistsEditPage, props: true },
    { path: '/award/:id/winners', name: 'edit-winners', component: WinnersEditPage }
  ]
});

let CategoriesRouter = new VueRouter({
  routes: [
    { name: 'categories', path: '/', component: CategoriesPage },
    { name: 'edit-categories', path: '/edit/:id', component: CategoriesEditPage }
  ]
});

/** 
 * Exporting functions to window to break up the app instead of using
 * the vue router. Vue Router can be factored into this later when 
 * authentication is resolved.
 */

window.Vue = Vue;
window.voting = {
  router: {
    awards: AwardsRouter, 
    categories: CategoriesRouter
  }
}