
var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'edit-profile',
            authenCode: null,
            memberName: '',
            member_data: {
                firstname: '',
                lastname: '',
                email: '',
                work_place_name: '',
                contact_name: '',
                contact_tel: ''
            },
            // latitude: '',
            // longitude: '',
            // green_list: [],
            messeageSigninError: ''
        }
    },
    created() {
        this.checkLoginData()
    },
    mounted() {},
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
                        authenCode: this.authenCode
                    })
                    .then((response) => {
                        //handle success
                        response = response.data
                        if (response.res_code == "00") {
                            this.memberName = response.member_data.firstname + ' ' + response.member_data
                                .lastname
                            this.member_data.firstname = response.member_data.firstname
                            this.member_data.lastname = response.member_data.lastname
                            this.member_data.email = response.member_data.email
                            this.member_data.work_place_name = response.member_data.work_place_name
                            this.member_data.contact_name = response.member_data.contact_name
                            this.member_data.contact_tel = response.member_data.contact_tel
                            // this.latitude = response.member_data.latitude
                            // this.longitude = response.member_data.longitude
                            // this.green_list = response.member_data.green_list
                        } else {
                            this.$cookies.remove('authen_login')
                            window.location.href = 'index.php'
                        }
                    })
                    .catch((response) => {
                        //handle error
                        window.location.href = 'index.php'
                    });
            } else {
                window.location.href = 'index.php'
            }
        },
        updateMemberData() {
            axios.post('api/action.php', {
                    action: 'update-member-data',
                    authenCode: this.authenCode,
                    // latitude: this.latitude,
                    // longitude: this.longitude,
                    // green_list: this.green_list
                    contact_name: this.member_data.contact_name,
                    contact_tel: this.member_data.contact_tel
                })
                .then((response) => {
                    //handle success
                    response = response.data

                    if (response.res_code == '00') {
                        const myToastEl = document.getElementById('submit_message_success')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    } else {
                        this.messeageSigninError = 'ระบบเกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
                        const myToastEl = document.getElementById('submit_message_error')
                        const toast = new bootstrap.Toast(myToastEl)
                        toast.show()
                    }

                })
                .catch((response) => {
                    //handle error
                    this.messeageSigninError = 'ระบบเกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
                    const myToastEl = document.getElementById('submit_message_error')
                    const toast = new bootstrap.Toast(myToastEl)
                    toast.show()
                })
        },
        checkForm(e) {
            this.updateMemberData()
        },
        getGreenTypeData() {
            axios.post('api/action.php', {
                    action: 'get-green-type',
                })
                .then((response) => {
                    //handle success
                    response = response.data
                })
                .catch((response) => {
                    //handle error
                })
        },
        clickCheckGreen(index, e) {
            this.green_list[index].selected = !this.green_list[index].selected
        }
    }
})