import { createApp } from 'vue'
import App from './App.vue'
import './styles/global.css'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import '@mdi/font/css/materialdesignicons.css'
import router from './router'


import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'darkTheme',
        themes: {
            darkTheme: {
                dark: true,
                colors: {
                    background: '#000000',
                    surface: '#0b0b0b',
                    primary: '#b30000',
                    'on-primary': '#ffffff',
                    error: '#ff3333',
                    'primary-darken-1': '#8b0000'
                }
            }
        }
    }
})
if (import.meta.env.DEV) {
    try {
        const keysToRemove = ['carrinho', 'resumo', 'pagamento']
        keysToRemove.forEach(k => {
            if (localStorage.getItem(k) !== null) {
                console.log('[DEV] Removendo chave de localStorage:', k)
                localStorage.removeItem(k)
            }
        })
    } catch (e) {
        console.warn('[DEV] Não foi possível limpar localStorage:', e)
    }
}

createApp(App).use(vuetify).use(router).mount('#app')
