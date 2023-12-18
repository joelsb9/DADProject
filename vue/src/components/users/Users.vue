<script setup>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { ref, computed, inject, onMounted, toRefs } from 'vue'
import avatarNoneUrl from '@/assets/avatar-none.png'
//import UserTable from "./UserTable.vue"
import { useUsersStore } from '../stores/users.js'

const router = useRouter()
const usersStore = useUsersStore()

const serverBaseUrl = inject('serverBaseUrl')

const photoFullUrl = (user) => {
    return user.photo_url
        ? serverBaseUrl + "/storage/fotos/" + user.photo_url
        : avatarNoneUrl;
}
const admins = ref(usersStore.admins)
const vcards = ref(usersStore.vcards)
const activeTable = ref('vcards');

const showTable = (tableName) => {
  activeTable.value = tableName;
  console.log('activeTable.value: ', activeTable.value)
};

</script>

<template>
    <div class="profile d-flex align-content-center justify-content-center">
        <div class="main-content p-3 rounded shadow-sm" style="background-color: #4a90e2;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="greeting">Transactions</h2>
                <div class="d-flex">
                    <button class="btn btn-primary mx-2" @click="showTable('vcards')">Vcards</button>
                    <button class="btn btn-primary mx-2" @click="showTable('admins')">Admins</button>
                </div>
            </div>
            <table v-if="activeTable === 'vcards'"  class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Max Debit</th>
                        <th>Blocked</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vcard in vcards" :key="vcard.phone_number">
                        <td class="align-middle text-center">
                            <img :src="photoFullUrl(vcard)" class="rounded-circle img_photo" />
                        </td>
                        <td class="align-middle text-start">{{ vcard.phone_number }}</td>
                        <td class="align-middle text-start">{{ vcard.email }}</td>
                        <td class="align-middle text-center">{{ vcard.balance }}</td>
                        <td class="align-middle text-center">{{ vcard.max_debit }}</td>
                        <td class="align-middle text-center">
                            <input type="checkbox" v-model="vcard.blocked" />
                        </td>
                        <td class="align-middle text-center"><i class="bi bi-eye-fill"></i></td>
                    </tr>
                </tbody>
            </table>

            <table v-if="activeTable === 'admins'"  class="table table-striped custom-table">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="admin in admins" :key="admin.id">
                        <td class="align-middle text-center">{{ admin.id }}</td>
                        <td class="align-middle text-start">{{ admin.name }}</td>
                        <td class="align-middle text-start">{{ admin.email }}</td>
                        <td class="align-middle text-center"><i class="bi bi-eye-fill"></i></td>
                    </tr>
                </tbody>
            </table>
                
            <!-- Pagination controls -->
            <nav class="mt-4" aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<style scoped>
.img_photo {
    width: 3.2rem;
    height: 3.2rem;
}
</style>