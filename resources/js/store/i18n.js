import Vue from 'vue'
import { MLInstaller, MLCreate, MLanguage } from 'vue-multilanguage'

Vue.use(MLInstaller)

export default new MLCreate({
    initial: 'english',
    save: process.env.NODE_ENV === 'production',
    languages: [
        new MLanguage('english').create({
            Linker: 'Linker',
            Account: 'Account',
            Join: "+ Join",
            Challenges: "Challenges",
            stripe: 'Buy models (STRIPE)',
            support: "Support",
            footer_use: 'Terms of use'
        }),

        new MLanguage('russian').create({
            Linker: 'Ссылка',
            Account: 'Аккаунт',
            Join: '+ Присоединиться',
            Challenges: 'Челлендж',
            stripe: 'Купить модели (STRIPE)',
            support: "Поддержка",
            footer_use: "Условия использования"
        })
    ],
    middleware: (component, path) => {
        // you can mutate path here
        // you should return path updated
        return path
    },
    gettingStrategy: 'default'
})
