var app = new Vue({
    el: '#vueapp',
    data() {
        return {
            menu_active: 'index',
            authenCode: null,
            memberName: '',
            provinces: [],
            organizations: [],
            energyType: [],
            greenType: [],
            activity_list: [],
            chartMultiYear: [],
            greenTypeActiveTab: 1,
            carbonFootprintTableData: [],
            energyListSearch: [],
            select_province_search: '',
            select_organization_search: '',
            select_green_search: '',
            green_marker: []
        }
    },
    created() {

        this.getYear()
        this.checkLoginData()
        this.getProvinceData()
        this.getOrganizationData()
        this.getEnergyTypeData()
        this.getGreenTypeData()
        this.getActivitiesList()
        this.getCarbonFootprintList()
    },
    mounted() {
        this.getGreenMarker(() => {
            this.initMap()
        })
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
        hexToRgb(hex) {
            var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },
        generateChartData(chartId, energyData) {
            // const labels = [];
            // for (let i = 2560; i <= 2565; ++i) {
            //     labels.push(i.toString());
            // }

            const labels = [];
            const datapoints = [];
            energyData.chart_data.forEach((y) => {
                labels.push(y.year)
                datapoints.push(y.sum_value)
            })
            const DATA_COUNT = energyData.chart_data.length;

            const rgb = 'rgb(' + this.hexToRgb(energyData.energy_color).r + ',' + this.hexToRgb(energyData
                .energy_color).g + ',' + this.hexToRgb(energyData.energy_color).b + ')'
            const rgba = 'rgba(' + this.hexToRgb(energyData.energy_color).r + ',' + this.hexToRgb(energyData
                .energy_color).g + ',' + this.hexToRgb(energyData.energy_color).b + ',0.5)'

            const data = {
                labels: labels,
                datasets: [{
                    label: energyData.energy_name_th,
                    data: datapoints,
                    borderColor: rgb,
                    backgroundColor: rgba,
                    fill: true,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }]
            };


            const config = {
                type: 'line',
                data: data,
                options: {
                    plugins: {
                        legend: false,
                        tooltip: true,
                        title: {
                            display: false
                        }
                    },
                    responsive: true,
                    plugins: {
                        title: {
                            display: false
                        }
                    },
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: energyData.energy_unit
                            }
                        }
                    },
                }
            };

            const myChart = new Chart(
                document.getElementById(chartId),
                config
            );
        },
        generateChartMultiData(chartId) {
            const datasets = []
            const labels = []

            this.chartMultiYear.forEach((y) => {
                labels.push(y.year)
            })
            this.energyType.forEach((item_energy, index) => {

                const datapoints = []
                item_energy.chart_data.forEach((y) => {
                    datapoints.push(y.sum_value)
                })
                const DATA_COUNT = item_energy.chart_data.length;

                const rgb = 'rgb(' + this.hexToRgb(item_energy.energy_color).r + ',' + this
                    .hexToRgb(
                        item_energy
                        .energy_color).g + ',' + this.hexToRgb(item_energy.energy_color).b + ')'
                const rgba = 'rgba(' + this.hexToRgb(item_energy.energy_color).r + ',' + this
                    .hexToRgb(item_energy
                        .energy_color).g + ',' + this.hexToRgb(item_energy.energy_color).b + ',0.5)'


                const dataValue = {
                    label: item_energy.energy_name_th,
                    data: datapoints,
                    borderColor: rgb,
                    backgroundColor: rgba,
                    fill: true,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }
                datasets.push(dataValue)
            })

            const data = {
                labels: labels,
                datasets: datasets
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    plugins: {
                        legend: false,
                        tooltip: true,
                        title: {
                            display: false
                        }
                    },
                    responsive: true,
                    plugins: {
                        title: {
                            display: false
                        },
                    },
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true
                            }
                        }
                    }
                },
            };

            const myChart = new Chart(
                document.getElementById(chartId),
                config
            );
        },
        getProvinceData() {
            axios.post('api/action.php', {
                    action: 'get-province'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.provinces = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        getOrganizationData() {
            axios.post('api/action.php', {
                    action: 'get-organization'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.organizations = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        getEnergyTypeData() {

            axios.post('api/action.php', {
                    action: 'get-search-energy-list-data'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.energyListSearch = response

                })
                .catch((response) => {
                    //handle error
                })

            axios.post('api/action.php', {
                    action: 'get-energy-type-data-chart'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.energyType = response
                    setTimeout(() => {
                        this.energyType.forEach((item, idx) => {
                            this.generateChartData(`chart_${item.energy_type_id}`, item)
                        })
                        this.generateChartMultiData('chart_multi_energy')
                    }, 500)


                })
                .catch((response) => {
                    //handle error
                })
        },
        getGreenTypeData() {
            axios.post('api/action.php', {
                    action: 'get-green-type'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.greenType = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        getActivitiesList() {
            axios.post('api/action.php', {
                    action: 'get-activites-list'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.activity_list = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        changeTabCarbonFootprint(energy_type_id) {
            this.greenTypeActiveTab = energy_type_id
            this.getCarbonFootprintList()
        },
        getCarbonFootprintList() {
            axios.post('api/action.php', {
                    action: 'get-carbon-footprint-list',
                    energy_type: this.greenTypeActiveTab
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    response.forEach((item, index) => {
                        response[index]['result_cbfp'] = parseFloat(item.value * item
                            .cal_carbon_footprint_value).toFixed(2)
                    })
                    this.carbonFootprintTableData = response

                })
                .catch((response) => {
                    //handle error
                })
        },
        searchEnergyData(typeSearch, id) {
            axios.post('api/action.php', {
                    action: 'get-search-energy-list-data',
                    type_search: typeSearch,
                    id: id
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.energyListSearch = response
                })
                .catch((response) => {
                    //handle error
                })
        },
        getYear() {
            axios.post('api/action.php', {
                    action: 'get-years'
                })
                .then((response) => {
                    //handle success
                    response = response.data
                    this.chartMultiYear = response
                })
        },
        getGreenMarker(callback = null) {
            axios.post('api/action.php', {
                    action: 'get-green-marker'
                })
                .then(async (response) => {
                    //handle success
                    response = await response.data
                    this.green_marker = await response

                    if (callback != null) {
                        await callback();
                    }
                })
        },
        initMap() {
            map = new L.Map('map')
            popup = new L.Popup()

            setTimeout(function() {
                map.invalidateSize()
            }, 200);

            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
            }).addTo(map)
            map.attributionControl.setPrefix('') // Don't show the 'Powered by Leaflet' text.

            // var local = new L.LatLng(13.8230634,100.9710903);

            // const markerLocation = new L.LatLng(13.736717, 100.523186);

            if (this.green_marker.length > 0) {
                this.green_marker.forEach((itemMarker, index) => {
                    const local = new L.LatLng(itemMarker.latitude, itemMarker.longitude)

                    const RedIcon = L.Icon.Default.extend({
                        options: {
                            iconUrl: itemMarker.green_pin_thumbnail,
                            iconSize: [26, 35] // size of the icon
                        }
                    });
                    const redIcon = new RedIcon();

                    if (index == 0) {
                        map.setView(local, 5)
                    }
                    L.marker(local, {
                        icon: redIcon
                    }).addTo(map).bindPopup(itemMarker.office_name_th)
                })
            } else {
                const local = new L.LatLng(13.736717, 100.523186)
                map.setView(local, 5)
            }

            /*map.on('click', this.onMapClick);

            const searchControl = new L.esri.Controls.Geosearch().addTo(map);

            const results = new L.LayerGroup().addTo(map);

            searchControl.on('results', function(data) {
                results.clearLayers();
                for (var i = data.results.length - 1; i >= 0; i--) {
                    results.addLayer(L.marker(data.results[i].latlng));
                }
            });*/
        }
    }
})