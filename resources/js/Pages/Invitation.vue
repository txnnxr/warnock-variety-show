<template>
    <Head>
        <meta property="og:title" content="{{this.show.name}} - {{inviteData.first_name}} Invitation"/>
        <title>{{inviteData.first_name}} Invitation</title>
    </Head>
    <Layout>

        <Form :action="formUrl" method="POST" class="card my-3">
            <div class="card-body">
                <h3 class="card-title">
                    <span v-if="this.invite">{{ inviteData.first_name }} {{ inviteData.middle_name }} {{ inviteData.last_name }} -</span><span v-if="!this.invite">Guest</span> RSVP
                    <Link :href="'/shows/'+this.show.id+'/view'">
                        <button type="button" class="btn btn-info"><i class="fa-solid fa-worm"></i>
                            View Show
                        </button>
                    </Link>
                </h3>
                <div class="form-control my-2" v-if="!this.invite">
                    <div class="row">
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="first_name" placeholder="First Name (required)">
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="form-control" type="text" name="last_name" placeholder="Last Name (optional)">
                        </div>
                    </div>
                </div>
                <div class="form-control my-2">
                    <div class="row ">
                        <h4 class="col-12">
                            <span v-if="this.show.at_capacity_attendants">Add to Attending Waitlist?</span>
                            <span v-else>Attending?</span>
                        </h4>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="response_status" id="yes" value="ATTENDING"
                               :checked="inviteData.response_status === 'ATTENDING' || inviteData.response_status.includes('PENDING')" v-model="inviteData.response_status">
                        <label class="form-check-label" for="yes">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="response_status" id="no" value="NO" :checked="inviteData.response_status === 'NO'" v-model="inviteData.response_status">
                        <label class="form-check-label" for="no">
                            No
                        </label>
                    </div>
                    <div class="form-check" v-if="!this.show.at_capacity_attendants">
                        <input class="form-check-input" type="radio" name="response_status" id="maybe" value="COWARD" :checked="inviteData.response_status === 'COWARD'"
                               v-model="inviteData.response_status">
                        <label class="form-check-label" for="maybe">
                            Maybe
                        </label>
                    </div>
                </div>
                <div class="talent-box form-control my-2" v-if="inviteData.response_status !== 'NO'">
                    <div class="row ">
                        <h4 class="col-12">
                            <span v-if="this.show.at_capacity_talents">Add to Talent Waitlist?</span>
                            <span v-else>Talent?</span>
                        </h4>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="talent" id="yes" value="1" :checked="inviteData.talent" v-model="inviteData.talent">
                        <label class="form-check-label" for="yes">
                            Ye
                        </label>
                    </div>
                    <div class="talent-write-in-box" v-if="inviteData.talent == '1'">
                        <div class="form-label">If you already know, what is your talent? (optional)</div>
                        <input class="form-text" type="text" name="talent_write_in" placeholder="" v-model="inviteData.talent_write_in">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="talent" id="no" value="0" :checked="!inviteData.talent" v-model="inviteData.talent">
                        <label class="form-check-label" for="no">
                            Nay
                        </label>
                    </div>
                </div>
                <div class="plus-one-box form-control my-2" v-if="inviteData.response_status !== 'NO' && inviteData.has_plus_one_option && !this.show.at_capacity_attendants">
                    <div class="row ">
                        <h4 class="col-12">Plus One?</h4>
                        <span><i>This does not include the option for the plus one to do a talent.</i></span>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="plus_one_status" id="yes" value="1" v-model="inviteData.plus_one_status">
                        <label class="form-check-label" for="yes">
                            Si si
                        </label>
                    </div>
                    <div class="plus-one-name-box" v-if="inviteData.plus_one_status == 1">
                        <div class="form-label">Who is this motherfucker?</div>
                        <input class="form-text" type="text" name="plus_one_name" placeholder="" v-model="inviteData.plus_one_name">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="plus_one_status" id="no" value="0" v-model="inviteData.plus_one_status">
                        <label class="form-check-label" for="no">
                            Non
                        </label>
                    </div>
                </div>

                <div class="notifications-box form-control my-2" v-if="inviteData.response_status !== 'NO'">
                    <div class="row">
                        <div class="col-md-6 my-1">
                            <label for="can_notify">Would you like to receive notifications about this event? (Notifications will only be sent out for: Cancellations, Reschedulings, and Waitlist
                                openings)</label>
                        </div>
                        <div class="col-md-6 my-1">
                            <input class="" type="checkbox" name="can_notify" value="1" :checked="inviteData.can_notify" v-model="inviteData.can_notify">
                        </div>
                    </div>
                    <div class="contact-box" v-if="inviteData.can_notify">
                        <p>Please enter your phone number to receive notifications:</p>
                        <div class="form-label">Phone:</div>
                        <input class="form-text" type="text" name="phone" placeholder="" v-model="inviteData.phone">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mx-3 mb-3" type="submit">Submit</button>
        </Form>
    </Layout>
</template>

<script>
import Layout from "../Shared/Layout";
import Form from "../Shared/Form";
import {Head} from "@inertiajs/inertia-vue3";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Invitation",
    components: {Layout, Form, Head, Link},
    props: {
        'show': Object,
        'invite': Object,
    },
    data() {
        return {
            'inviteData': {
                'first_name': (this.invite && this.invite.first_name) ? this.invite.first_name : null,
                'middle_name': (this.invite && this.invite.middle_name) ? this.invite.middle_name : null,
                'last_name': (this.invite && this.invite.last_name) ? this.invite.last_name : null,
                'response_status': (this.invite && this.invite.response_status && !this.invite.response_status.includes('PENDING')) ? this.invite.response_status : 'ATTENDING',
                'talent': (this.invite && this.invite.talent) ? this.invite.talent : '1',
                'plus_one_status': (this.invite && this.invite.plus_one_status) ? this.invite.plus_one_status : 1,
                'plus_one_name': (this.invite && this.invite.plus_one_name) ? this.invite.plus_one_name : null,
                'has_plus_one_option': (this.invite && this.invite.has_plus_one_option == 1),
                'can_notify': (this.invite && this.invite.can_notify == 1),
                'phone': (this.invite) ? this.invite.phone : null
            },
            'formUrl': (this.invite) ? '/invite/respond/' + this.invite.id : '/invite/' + this.show.id + '/guest-invitation',
        }
    }
}
</script>

<style scoped>

</style>
