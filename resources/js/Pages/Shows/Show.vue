<template>
    <Head>
        <meta property="og:title" content="{{show.name}}"/>
        <title>{{show.name}}</title>
    </Head>
    <Layout>
        <div class="card p-3">
            <h2 class="card-title text-center mt-3">{{ show.name }}</h2>
            <img class="invitation-logo" src="/images/PetsOnly.png" alt="">
            <div class="card-body mb-3" v-html="show.description"></div>
        </div>
        <div class="card mt-3" v-if="new Date(show.date) > Date.now() && show.public_rsvp_open">
            <div class="card-body p-4">
                <div class="col-md-12 my-1">
                    <Link :href="'/show/'+show.id+'/guest-invitation'">
                        <button class="form-control btn btn-primary" type="button">RSVP</button>
                    </Link>
                </div>
            </div>
        </div>
        <div class="card my-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 col-sm-3">
                        <h3 class="headers">Attending ({{ attending_count() }})</h3>
                        <GuestList :invites="show.attending_invites"/>
                    </div>
                    <div v-if="!show.at_capacity_attendants" class="col-6 col-sm-3">
                        <h3>Maybe ({{ show.maybe_invites.length }})</h3>
                        <GuestList :invites="show.maybe_invites"/>
                    </div>
                    <div v-if="!show.at_capacity_attendants" class="col-6 col-sm-3">
                        <h3>No ({{ show.no_invites.length }})</h3>
                        <GuestList :invites="show.no_invites"/>
                    </div>
<!--                    <div v-if="!show.at_capacity_attendants" class="col-6 col-sm-3">-->
<!--                        <h3>Pending ({{ show.pending_invites.length }})</h3>-->
<!--                        <GuestList :invites="show.pending_invites"/>-->
<!--                    </div>-->
                    <div v-else class="col-6 col-sm-3">
                        <h3>Attendance Waitlist ({{ show.attending_waitlist_invites.length }})</h3>
                        <GuestList :invites="show.attending_waitlist_invites"/>
                    </div>
                    <div v-if="show.at_capacity_talents" class="col-6 col-sm-3">
                        <h3>Exhibition Waitlist ({{ show.talent_waitlist_invites.length }})</h3>
                        <GuestList :invites="show.talent_waitlist_invites"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="card my-3">
            <div class="card-body">
                <h3 class="card-title">Current Line Up</h3>
                <h6>(in no particular order)</h6>
                <ul class="inviteeList">
                    <li v-for="exhibitor in show.attending_talents">{{ exhibitor.first_name }} {{ exhibitor.middle_name }} {{ exhibitor.last_name }} <span
                        v-if="exhibitor.talent_write_in"> - {{ exhibitor.talent_write_in }}</span></li>
                </ul>
            </div>
        </div>
    </Layout>
</template>

<script>
import GuestList from "../../Shared/GuestList";
import Layout from "../../Shared/Layout";
import {Link} from "@inertiajs/inertia-vue3";
export default {
    name: "Show.vue",
    components: {GuestList, Layout, Link},
    props: {
        'show': Object
    },
    data() {
        return {
            attending_count: () => this.show.attending_invites.length + this.show.attending_invites.reduce((sum, attending_invites) => {
                return sum += attending_invites.plus_one_status;
            }, 0)
        }
    }
}
</script>

<style scoped>

</style>
