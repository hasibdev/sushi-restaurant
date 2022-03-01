export default async function auth({ next, router }) {
    const token = window.jara.getCookie('token')

    if (!token) router.push('/login')

    return next()
}
