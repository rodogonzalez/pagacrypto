import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/static/sass/app.scss', 'resources/js/app.js'],
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false
                }
            }
        })
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    },
    optimizeDeps: {
        exclude: ['vue-demi']
    },

    base: '/build/',        

    build: {
        emptyOutDir: false,
        manifest: true,
        //manifest: '/manifest.json',
        rollupOptions: {
            // overwrite default .html entry
            input: {
                main: '/resources/js/app.js'
            },            
            output: {
                //manifest: '/build/manifest.json',
                assetFileNames: ({ name }) => {
                    console.log(name)
                    if (/\.(gif|jpe?g|png|svg)$/.test(name ?? '')) {
                        return 'images/[name]-[hash].[ext]'
                    }

                    if (/\.(ttf)$/.test(name ?? '')) {
                        return 'fonts/[name].[ext]'
                    }
                    return '[name].[ext]'
                },
                entryFileNames: `[name].js`,
                chunkFileNames: `chunks/[name].[hash].js`
                // assetFileNames: `[name].[ext]`
            }
        }
    }
})
