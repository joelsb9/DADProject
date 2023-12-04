import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
<<<<<<< HEAD
<<<<<<< HEAD
=======
import vue from '@vitejs/plugin-vue';
>>>>>>> 0ea4eb2 (API-CRUD for Transactions,Vcards,Users / All models Done, 3 Controllers(Transactions,Vcards,Users) / Routes for (Transactions,Vcards,Users) / Requests for (Transactions,Vcards,Users) / Resources for (Transactions,Vcards,Users) / tested all besides Transactions(Update,Delete,Restore,getTransactionsForVcards))
=======
import vue from '@vitejs/plugin-vue';
>>>>>>> 0ea4eb24327d4e3b552da19c853489caa039c552

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
<<<<<<< HEAD
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
=======
=======
>>>>>>> 0ea4eb24327d4e3b552da19c853489caa039c552
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
<<<<<<< HEAD
>>>>>>> 0ea4eb2 (API-CRUD for Transactions,Vcards,Users / All models Done, 3 Controllers(Transactions,Vcards,Users) / Routes for (Transactions,Vcards,Users) / Requests for (Transactions,Vcards,Users) / Resources for (Transactions,Vcards,Users) / tested all besides Transactions(Update,Delete,Restore,getTransactionsForVcards))
=======
>>>>>>> 0ea4eb24327d4e3b552da19c853489caa039c552
});
