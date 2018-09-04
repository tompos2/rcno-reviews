const CurrentlyReading = {
    template: '#reading-template',
    data () {
        return {
            all_updates: [],
            curr_index:  null,
            is_loading:  true,
            data_source: ''
        };
    },
    created () {
        this.fetchData();        
    },
    computed: {
        percentage: function () {
            return Math.round((this.all_updates[this.curr_index].current_page / 
                this.all_updates[this.curr_index].num_of_pages) * 100);
        },
        completed: function () {
            return this.percentage + '% ' + rcno_currently_reading.completed;
        }
    },
    methods: {
        fetchData: function () {

            if (localStorage.getItem('rcno_all_updates')) {
                const data       = JSON.parse(localStorage.getItem('rcno_all_updates'));
                this.all_updates = data;
                this.curr_index  = data.length - 1;
                this.is_loading  = false;
                this.data_source = 'local-storage';

                return;
            }

            fetch('http://rcno.local/wp-json/rcno/v1/currently-reading', {
                headers: new Headers({
                    method: 'GET',
                    'X-WP-Nonce': rcno_currently_reading.nonce
                })
            }).then(function(response) {
                return response.json();
            }).then(function (data) {
                this.all_updates = data;
                this.curr_index  = data.length - 1;
                this.is_loading  = false;
                this.data_source = 'remote-server';

                localStorage.setItem('rcno_all_updates', JSON.stringify(data));
            }.bind(this)).catch(function(err) {
                console.log(err)
            });
        },
        previous: function () {
            if (this.curr_index > 0) {
                this.curr_index--;
            }
        },
        next: function () {
            if (this.curr_index < this.all_updates.length - 1) {
                this.curr_index++;
            }
        }
    }
};

const app = new Vue({
    el: '#currently-reading',
    components: {
        'currently-reading': CurrentlyReading,
    },
});

