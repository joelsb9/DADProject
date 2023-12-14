import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '../components/stores/user.js'
import HomeView from '../views/HomeView.vue'
import Dashboard from "../components/Dashboard.vue"
import Login from "../components/auth/Login.vue"
import RegisterVcard from "../components/auth/RegisterVcard.vue"
import ChangePassword from "../components/auth/ChangePassword.vue"
// import Categories from "../components/categories/Categories.vue";
// import Vcards from "../components/vcards/Vcards.vue";
// import Admins from "../components/admins/Admins.vue";
// import Transactions from "../components/transactions/Transactions.vue";
// import DefaultCategories from "../components/default-categories/DefaultCategories.vue";

let handlingFirstRoute = true

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'About',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/register-vcard',
      name: 'RegisterVcard',
      component: RegisterVcard
    },
    {
      path: '/password',
      name: 'ChangePassword',
      component: ChangePassword
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: Dashboard
    },
    {
      path: '/transactions',
      name: 'Transactions',
      //component: Transactions,
    },
    {
      path: '/profile',
      name: 'Profile',
      //component: Profile,
    }
  ]
});
// {
//   path: '/categories',
//   name: 'Categories',
//   component: Categories,
// },
// {
//   path: '/categories/:categoryId/restore',
//   name: 'RestoreCategory',
//   component: Categories,
//   props: route => ({ categoryId: parseInt(route.params.categoryId), restore: true })
// },
// {
//   path: '/vcards',
//   name: 'Vcards',
//   component: Vcards,
// },
// {
//   path: '/vcards/:vcardId/restore',
//   name: 'RestoreVcard',
//   component: Vcards,
//   props: route => ({ vcardId: parseInt(route.params.vcardId), restore: true })
// },
// {
//   path: '/vcards/me',
//   name: 'MyVcard',
//   component: Vcards,
//   props: { myVcard: true }
// },
// {
//   path: '/vcards/update-password/:vcard',
//   name: 'UpdateVcardPassword',
//   component: Vcards,
//   props: route => ({ vcardId: parseInt(route.params.vcard), updatePassword: true })
// },
// {
//   path: '/vcards/:vcard/transactions',
//   name: 'VcardTransactions',
//   component: Transactions,
//   props: route => ({ vcardId: parseInt(route.params.vcard) })
// },
// {
//   path: '/admins',
//   name: 'Admins',
//   component: Admins,
// },
// {
//   path: '/admins/me',
//   name: 'MyAdmin',
//   component: Admins,
//   props: { myAdmin: true }
// },
// {
//   path: '/admins/update-password/:admin',
//   name: 'UpdateAdminPassword',
//   component: Admins,
//   props: route => ({ adminId: parseInt(route.params.admin), updatePassword: true })
// },
// {
//   path: '/transactions',
//   name: 'Transactions',
//   component: Transactions,
// },
// {
//   path: '/transactions/:transactionId/restore',
//   name: 'RestoreTransaction',
//   component: Transactions,
//   props: route => ({ transactionId: parseInt(route.params.transactionId), restore: true })
// },
// {
//   path: '/default-categories',
//   name: 'DefaultCategories',
//   component: DefaultCategories,
// },
// {
//   path: '/default-categories/:defaultCategoryId/restore',
//   name: 'RestoreDefaultCategory',
//   component: DefaultCategories,
//   props: route => ({ defaultCategoryId: parseInt(route.params.defaultCategoryId), restore: true })
// },
// Add more routes as needed...




router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore()
  if (handlingFirstRoute) {
    handlingFirstRoute = false
    await userStore.restoreToken()
  }
  if ((to.name == 'Login') || (to.name == 'RegisterVcard')) {
    next()
    return
  }
  if (!userStore.user) {
    next({ name: 'Login' })
    return
  }
  // if (to.name == 'Reports') {
  //   if (userStore.user.type != 'A') {
  //     next({ name: 'home' })
  //     return
  //   }
  // }
  // if (to.name == 'User') {
  //   if ((userStore.user.type == 'A') || (userStore.user.id == to.params.id)) {
  //     next()
  //     return
  //   }
  //   next({ name: 'home' })
  //   return
  // }
  next()
})


export default router;