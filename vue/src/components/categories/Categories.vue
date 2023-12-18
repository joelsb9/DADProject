<script setup>
import { ref, onMounted } from 'vue'
import { useCategoriesStore } from '../stores/categories.js'
import { useUsersStore } from '../stores/users.js'

const store = useCategoriesStore()
const showEditModal = ref(false)
const showDeleteModal = ref(false)
const selectedCategory = ref(null)
const categoryTypeOptions = ['Debit', 'Credit']
const userStore = useUsersStore()
console.log(userStore)
const vcardId = userStore.vcards.value.id

onMounted(async () => {
  console.log(userStore.vcards)
  await store.loadCategories(vcardId)
})

const openEditModal = (category) => {
  selectedCategory.value = { ...category }
  showEditModal.value = true
}

const openDeleteModal = (category) => {
  selectedCategory.value = category
  showDeleteModal.value = true
}

const editCategory = async () => {
  await store.updateCategory(selectedCategory.value)
  showEditModal.value = false
}

const deleteCategory = async () => {
  await store.deleteCategory(selectedCategory.value.id)
  showDeleteModal.value = false
}
</script>

<template>
  <div class="main-content">
    <h2>Categories</h2>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in store.categories" :key="category.id">
          <td>{{ category.type }}</td>
          <td>{{ category.name }}</td>
          <td>
            <button @click="openEditModal(category)">Edit</button>
            <button @click="openDeleteModal(category)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Edit Modal -->
    <div v-if="showEditModal">
      <h2>Edit Category</h2>
      <form @submit.prevent="editCategory">
        <label for="categoryType">Type</label>
        <select id="categoryType" v-model="selectedCategory.value.type">
          <option v-for="option in categoryTypeOptions" :value="option">{{ option }}</option>
        </select>

        <label for="categoryName">Name</label>
        <input id="categoryName" v-model="selectedCategory.value.name" type="text" />

        <button type="submit">Save</button>
        <button type="button" @click="showEditModal.value = false">Cancel</button>
      </form>
    </div>

    <!-- Delete Modal -->
    <div v-if="showDeleteModal">
      <h2>Delete Category</h2>
      <p>Are you sure you want to delete this category?</p>
      <button @click="deleteCategory">Confirm</button>
      <button @click="showDeleteModal.value = false">Cancel</button>
    </div>
  </div>
</template>

<style scoped>
.slide-fade-enter-active {
    transition: all .3s ease;
}

.slide-fade-leave-active {
    transition: all .3s ease;
}

.slide-fade-enter,
.slide-fade-leave-to {
    transform: translateY(10px);
    opacity: 0;
}

.description-content {
    overflow: hidden;
}

.description-row .description-content {
    max-height: 100px;
    /* Adjust this to fit your content */
}

.profile {
    height: 100vh;
    background-color: #62beff;
}

.description-row {
    border-top: none;
    background-color: #fff;
    /* Change this to match the color of your other rows */
}

.clickable {
    cursor: pointer;
}

.main-content {
    max-width: 1200px;
    margin: auto;
    font-family: 'Arial', sans-serif;
    background-color: #fff;
    border: 1px solid #b8daff;
    border-radius: 20px;
}

.greeting {
    color: #1890ff;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}

thead th {
    font-weight: 300;
    /* Make the column titles thinner */
    color: #fff;
    /* Make the column titles white */
    padding: 10px;
    /* Add some padding to the column titles */
    background-color: #1890ff;
    /* Add a blue background to the column titles */
}

tbody td {
    color: #333;
    padding: 20px;
    border: 1px solid #ddd;
    /* Add a light gray border to the table cells */
}

.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
</style>