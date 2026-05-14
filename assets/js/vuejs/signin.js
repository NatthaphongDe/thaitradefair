var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'index',
            authenCode: null,
            username: '',
            password: '',
            messeageSigninError: ''
        }
    },
    created() {
        this.checkLoginData()
    },
    methods: {
        redirectPage(url) {
            window.location.href = url
        },
        logout() {
            this.$cookies.remove('authen_login')
            window.location.href = 'index.php'
        },
        checkLoginData() {
            if (this.$cookies.get('authen_login') != undefined) {
                this.authenCode = this.$cookies.get('authen_login')

                axios.post('api/action.php', {
                        action: 'check-auth-login',
                        authenCode: this.authenCode,
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        if (response.res_code == '00') {
                            window.location.href = 'index.php'
                        }
                    })
                    .catch((response) => {
                        //handle error

                    });
            }
        },
        submitSignIn() {
            if (app.username != '' && app.password != '') {
                axios.post('api/signin.php', {
                        action: 'signin',
                        username: app.username,
                        password: app.password
                    })
                    .then((response) => {
                        //handle success

                        response = response.data
                        if (response.res_code == '00') {

                            const myToastEl = document.getElementById('signin_message_success')
                            const toast = new bootstrap.Toast(myToastEl)
                            toast.show()
                            myToastEl.addEventListener('shown.bs.toast', () => {
                                setTimeout(async () => {
                                    await this.$cookies.set('authen_login', response
                                        .authen_code)
                                    window.location.href = "index.php"
                                }, 2000);
                            })
                        } else {
                            this.messeageSigninError = response.res_message
                            const myToastEl = document.getElementById('signin_message_error')
                            const toast = new bootstrap.Toast(myToastEl)
                            toast.show()
                        }
                    })
                    .catch((response) => {
                        //handle error
                        this.messeageSigninError = 'ระบบผิดพลาด กรุณาลองใหม่อีกครั้ง'
                        const myToastEl = document.getElementById('signin_message_error')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    });
            } else {
                this.messeageSigninError = 'กรุณาระบุ Username และ Password ให้ครบถ้วน'
                const myToastEl = document.getElementById('signin_message_error')
                const toast = new bootstrap.Toast(myToastEl)
                toast.show()
            }

        }
    }
})