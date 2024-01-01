<template>
  <div>
      <h1>123123</h1>
    <div class="container mb-5">
      <breadcrumbs/>
    </div>
    <div class="d-flex content-right double-column">
      <div class="container">
        <p class="block-title tr-none">Account</p>
        <div class="d-flex flex-column">
          <!-- <div class="profile">
            <div class="profile__main">
              <img
                :src="
                  user.photo == null
                    ? '/img/user.png'
                    : '/storage/avatars/' + user.photo
                "
                alt=""
              />
              <p class="name">{{ user.name }}</p>
              <p class="rating">
                {{ user.rang ? user.rang.name : "" }} ({{ user.rating }})
              </p>
            </div>
            <form ref="userData" class="profile__info">
              <p class="edit" data-type="name">
                <span>Name</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="name"
                  v-model="eFormData.name"
                  :disabled="eformToggle.name == false ? true : false"
                />
                <span @click="toggleData('name')">{{
                  !eformToggle.name ? "Edit" : "Save"
                }}</span>
              </p>
              <p class="edit" data-type="specialization">
                <span>Specialization:</span>
                <input
                  class="user_data-input"
                  type="text"
                  v-model="eFormData.specialization"
                  name="specialization"
                  :disabled="eformToggle.specialization == false ? true : false"
                />
                <span
                  @click="toggleData('specialization')
                  "
                  >{{ !eformToggle.specialization ? "Edit" : "Save" }}</span
                >
              </p>
              <p><span>E-mail:</span>{{ user.email }}</p>
              <p class="edit" data-type="location">
                <span>Locations:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="location"

                  v-model="eFormData.location"
                  :disabled="eformToggle.location == false ? true : false"
                />
                <span @click="toggleData('location')">{{
                  !eformToggle.location ? "Edit" : "Save"
                }}</span>
              </p>
              <br />
              <p><span>Rating:</span>{{ user.rating }}</p>
              <p class="edit" data-type="website">
                <span>Web site:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="website"
                  v-model="eFormData.website"
                  :disabled="eformToggle.website == false ? true : false"
                />
                <span @click="toggleData('website')">{{
                  !eformToggle.website ? "Edit" : "Save"
                }}</span>
              </p>
              <p class="edit" data-type="signature">
                <span>Signature:</span>
                <input
                  class="user_data-input"
                  type="text"
                  name="signature"

                  v-model="eFormData.signature"
                  :disabled="eformToggle.signature == false ? true : false"
                />
                <span @click="toggleData('signature')">{{
                  !eformToggle.signature ? "Edit" : "Save"
                }}</span>
              </p>
            </form>
          </div> -->
        </div>

        <vnav></vnav>
      </div>
      <!-- <Challenges /> -->
    </div>

  </div>
</template>


<script>
export default {
    name:"HeadElement",
}
</script>