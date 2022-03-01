
export default async function auth({ next, router }) {
    const token = window.jara.getCookie('token')

    if (token) {
        return router.push('/')
    }

    return next()
}
