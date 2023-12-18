import axios from 'axios'
import { ref, inject, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from "vue-toastification"

export const useCategoriesStore = defineStore('categories', () => {
    const toast = useToast()
    const categories = ref([])

    async function loadCategories(vcardId) {
        try {
            const response = await axios.get(`vcards/${vcardId}/categories`);
            categories.value = response.data;
        } catch (error) {
            console.error('Error loading categories:', error)
            clearCategories()
        }
    };

    async function createCategory(category) {
        try {
            const response = await axios.post(`/categories`, category);
            categories.value.push(response.data);
            toast.success(`Category ${response.data.name} has been created successfully!`)
        } catch (error) {
            console.error('Error creating category:', error)
        }
    };

    async function editCategory(category) {
        try {
            const response = await axios.put(`/categories/${category.id}`, category);
            const index = categories.value.findIndex(c => c.id === category.id)
            if (index !== -1) {
                categories.value[index] = response.data;
                toast.success(`Category ${response.data.name} has been updated!`)
            }
        } catch (error) {
            console.error('Error editing category:', error)
        }
    };

    async function deleteCategory(categoryId) {
        try {
            await axios.delete(`/categories/${categoryId}`);
            categories.value = categories.value.filter(c => c.id !== categoryId);
            toast.success(`Category has been deleted!`)
        } catch (error) {
            console.error('Error deleting category:', error)
        }
    };

    function clearCategories() {
        categories.value = null
    }

    return {
        categories,
        clearCategories,
        loadCategories,
        createCategory,
        editCategory,
        deleteCategory,
    };
})