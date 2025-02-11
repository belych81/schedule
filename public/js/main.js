window.addEventListener('load', async () => {
    console.log(navigator)
    if('serviceWorker' in navigator){
        try {
            const reg = await navigator.serviceWorker.register('/sw.js')
            console.log('success', reg)
        } catch(e) {
            console.log('fail')
        }
    }
})