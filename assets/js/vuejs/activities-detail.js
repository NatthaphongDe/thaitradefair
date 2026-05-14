var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'activities',
            authenCode: null,
            memberName: '',
            activityId: null,
            activity_data: {}
        }
    },
    created() {
        this.checkLoginData()

        const params = new URLSearchParams(window.location.search)
        this.activityId = (params.has('activityId')) ? params.get('activityId') : null

        if (this.activityId !== null) {
            this.getActivityData()
        }
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
                        } else {
                            this.$cookies.remove('authen_login')
                            window.location.href = "index.php";
                        }
                    })
                    .catch((response) => {
                        //handle error
                        this.$cookies.remove('authen_login')
                        window.location.href = "index.php";

                    });
            }
        },
        backToActivityList() {
            window.location.href = 'activities.php'
        },
        getActivityData() {
            axios.post('api/action.php', {
                    action: 'get-activity-data',
                    activity_id: this.activityId
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.activity_data = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        generateThaiDate(date_value) {
            const getDateOnly = date_value.split(' ')[0]
            const splitDate = getDateOnly.split('-')
            const date = new Date(splitDate[0], splitDate[1]-1, splitDate[2])

            const result = date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
            return result;
        }
    }
})