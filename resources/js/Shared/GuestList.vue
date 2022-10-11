<template>
    <ul class="inviteeList">
        <li v-for="invite in invites">
            <i v-if="invite.response_status == 'ATTENDING'" class="fa-regular fa-circle-check"></i>
            <i v-if='invite.response_status == "COWARD"' class="fa-regular fa-circle-question"></i>
            <i v-if='invite.response_status == "NO"' class="fa-regular fa-circle-xmark"></i>
            <i v-if="invite.response_status.includes('PENDING')" class="fa-solid fa-circle-exclamation"></i>
            <i v-if="invite.talent && !invite.response_status.includes('PENDING')" class="fa-solid fa-otter"></i>
            <i v-if="!invite.talent" class="fa-solid fa-bugs"></i>
            {{invite.first_name}} {{invite.middle_name}} {{invite.last_name}}
            <i v-if="invite.plus_one_status">(+1)</i>
        </li>
    </ul>
</template>

<script>
export default {
    name: "GuestList",
    props: {
        'invites': Array
    }
}
</script>

<style scoped>

</style>
