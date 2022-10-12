<template>
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
                        <button class="form-control btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#rsvpModal">RSVP</button>
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
                    <div v-if="!show.at_capacity_attendants" class="col-6 col-sm-3">
                        <h3>Pending ({{ show.pending_invites.length }})</h3>
                        <GuestList :invites="show.pending_invites"/>
                    </div>
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

<!--        <div class="modal fade" id="rsvpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
<!--            <div class="modal-dialog">-->
<!--                <div class="modal-content">-->
<!--                    <div class="modal-header">-->
<!--                        <h5 class="modal-title" id="exampleModalLabel">RSVP</h5>-->
<!--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
<!--                    </div>-->
<!--                    <Form class="" :action="'/shows/'+show.id+'/invite/guest-request'" method="POST">-->
<!--                        <div class="modal-body row">-->
<!--                            <div class="col-md-6 my-1">-->
<!--                                <input class="form-control" type="text" name="first_name" placeholder="First Name (required)">-->
<!--                            </div>-->
<!--                            <div class="col-md-6 my-1">-->
<!--                                <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">-->
<!--                            </div>-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="form-control my-2">-->
<!--                                    <div class="row ">-->
<!--                                        <h4 class="col-12">Attending?</h4>-->
<!--                                    </div>-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING" checked>-->
<!--                                        <label class="form-check-label" for="yes">-->
<!--                                            Yes-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD">-->
<!--                                        <label class="form-check-label" for="maybe">-->
<!--                                            Maybe-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="radio" name="response_status" id="no" value="NO">-->
<!--                                        <label class="form-check-label" for="no">-->
<!--                                            No-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="talent-box form-control my-2">-->
<!--                                    <div class="row ">-->
<!--                                        <h4 class="col-12">Talent?</h4>-->
<!--                                    </div>-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="radio" name="talent" id="yes" value="1" checked>-->
<!--                                        <label class="form-check-label" for="yes">-->
<!--                                            Ye-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                    <div class="form-check">-->
<!--                                        <input class="form-check-input" type="radio" name="talent" id="no" value="0">-->
<!--                                        <label class="form-check-label" for="no">-->
<!--                                            Nay-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="modal-footer">-->
<!--                            <button type="submit" class="btn btn-primary text-end">Submit</button>-->
<!--                        </div>-->
<!--                    </Form>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
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
