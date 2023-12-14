import axios from 'axios'
import { io } from 'socket.io-client'
import FieldErrorMessage from './components/global/FieldErrorMessage.vue'
import ConfirmationDialog from './components/global/ConfirmationDialog.vue'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import Toast from "vue-toastification"
import 'bootstrap'
// Import the Toast CSS (or use your own)!
import "vue-toastification/dist/index.css"
// Import Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
// Import FontAwesome icons (or any other icon library)
import '@fortawesome/fontawesome-free/css/all.min.css';



const app = createApp(App)

const apiDomain = import.meta.env.VITE_API_DOMAIN
//console.log(apiDomain)
const wsConnection = import.meta.env.VITE_WS_CONNECTION

app.provide('socket', io(wsConnection))

app.provide('serverBaseUrl', apiDomain)  
// Default Axios configuration
axios.defaults.baseURL = apiDomain + '/api'
axios.defaults.headers.common['Content-type'] = 'application/json'

// Default/Global Toast configuration
app.use(Toast, {
  position: "top-center",
  timeout: 3000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: true,
  hideProgressBar: true,
  closeButton: "button",
  icon: true,
  rtl: false
})

//console.log(apiDomain+'/api')
app.use(createPinia())
app.use(router)
app.component('FieldErrorMessage', FieldErrorMessage)
app.component('ConfirmationDialog', ConfirmationDialog)

app.mount('#app')