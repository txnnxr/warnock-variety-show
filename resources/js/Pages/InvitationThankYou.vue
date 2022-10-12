<template>
    <Layout>
        <div class="card my-3">
            <div class="card-body">
                <h5 class="card-title">Thank you {{ invite.first_name }} {{ invite.middle_name }}
                    {{ invite.last_name }}! Your response has been saved!</h5>
                <div class="form-control my-2">
                    <div class="row">
                        <div class="col-12">Attending?</div>
                    </div>
                    <div class="row">
                        <div class="col-12" >
                            <span v-if="!invite.is_on_attending_waitlist">
                                <span v-if="invite.response_status === 'COWARD'">MAYBE</span>
                                <span v-if="invite.response_status !== 'COWARD'">{{ invite.response_status }}</span>
                                <span v-if="invite.guest_request">- REQUESTED</span>
                            <i v-if="invite.plus_one_status">+1 <span v-if="invite.plus_one_name">({{ invite.plus_one_name }})</span></i>
                            </span>
                            <span v-if="invite.is_on_attending_waitlist">We're at max capacity. No more room at the swim club. You're on the waitlist to attend. We'll let you know if a spot opens up.</span>
                        </div>
                    </div>
                </div>
                <div class="form-control my-2" v-if="invite.response_status == 'ATTENDING'">
                    <div class="row ">
                        <div class="col-12">Talent?</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span v-if="!invite.is_on_talent_waitlist">
                                <span v-if="invite.talent">
                                    Yes
                                    <span v-if="!invite.talent_write_in">
                                        -
                                        <i>{{ invite.talent_write_in }}</i>
                                    </span>
                                </span>
                                <span v-if="!invite.talent">
                                    No
                                </span>
                            </span>
                            <span v-if="invite.is_on_talent_waitlist">
                                Sorry, jabroni. You're getting big leagued. You're on the waitlist to do a talent. We'll let you know if a spot opens up.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-control my-2">
                    <div class="row ">
                        <div class="col-12">Notifications</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span v-if="invite.can_notify">
                                Yes
                            </span>
                            <span v-else>
                                No
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-left" v-if="invite.response_status == 'ATTENDING'">
                        <Form method="post" :action="'/invites/'+invite.id+'/generate-ics'">
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-kiwi-bird"></i> Add
                                to Calendar
                            </button>
                        </Form>
                    </div>
                    <div class="col text-center">
                        <Link :href="'/shows/'+show.id+'/edit'">
                            <button type="submit" class="btn btn-info"><i class="fa-solid fa-worm"></i>
                                View Show
                            </button>
                        </Link>
                    </div>
                    <div class="col text-end">
                        <Link :href="'/invites/'+invite.id+'/edit'">
                            <button type="submit" class="btn btn-warning"><i class="fa-solid fa-hippo"></i>
                                Update Response
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../Shared/Layout";
import Form from "../Shared/Form";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "InvitationThankYou",
    components: {Form, Layout, Link},
    props: {
        'invite': Object,
        'show': Object,
    }
}
</script>

<style scoped>

</style>
