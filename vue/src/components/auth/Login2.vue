<script setup>
//import axios from 'axios'
import { useToast } from "vue-toastification"
import { useRouter } from 'vue-router'
import { useAdminStore } from '../stores/admin.js'
//import { useVcardStore } from '../stores/vcard.js'
import { ref } from 'vue'

const toast = useToast()
const router = useRouter()
const userStore = useAdminStore()

const credentials = ref({
    username: '',
    password: ''
})

const emit = defineEmits(['login'])

const login = async () => {
    if (await userStore.login(credentials.value)) {
        toast.success('User ' + userStore.user.name + ' has entered the application.')
        emit('login')
        router.back()
    } else {
        credentials.value.password = ''
        toast.error('User credentials are invalid!')
    }
}

</script>


<template>
    <form class="row g-3 needs-validation" novalidate @submit.prevent="login">
        <section class="vh-100" style="background-color: #508bfc;">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card shadow-2-strong" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <h3 class="mb-5">Sign in</h3>

                                <div class="form-outline mb-4">
                                    <input v-model="$credentials.username" type="email" id="typeEmailX-2" class="form-control form-control-lg" />
                                    <label class="form-label" for="typeEmailX-2">Email</label>
                                </div>

                                <div class="form-outline mb-4">
                                    <input v-model="$credentials.password" type="password" id="typePasswordX-2" class="form-control form-control-lg" />
                                    <label class="form-label" for="typePasswordX-2">Password</label>
                                </div>

                                <!-- Checkbox -->
                                <!-- <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3"> Remember password </label>
                                </div> -->

                                <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>

                                <hr class="my-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</template>
