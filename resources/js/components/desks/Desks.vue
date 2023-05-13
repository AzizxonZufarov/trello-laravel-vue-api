<template>
  <div class="container">
    <h1>Доски</h1>
    <form @submit.prevent="addNewDesk">
      <div class="form-group">
        <input
          type="text"
          v-model="name"
          class="form-control"
          :class="{
            'is-invalid': v$.name.$error,
          }"
        />
        <div class="invalid-feedback" v-if="!v$.name.required">Обяз поле</div>
        <div class="invalid-feedback" v-if="!v$.name.maxLength">
          Макс кол-во: {{ v$.name.$params.maxLength.max }}
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
    <div class="alert alert-danger mt-3" role="alert" v-if="errored">
      A simple danger alert—check it out!<br />
      {{ errors[0] }}
    </div>
    <div class="row">
      <div class="col-lg-4" v-for="desk in desks" :key="desk.id">
        <div class="card mt-3">
          <router-link
            class="card-body"
            :to="{ name: 'showDesk', params: { deskId: desk.id } }"
          >
            <h4 class="card-title">{{ desk.name }}</h4>
          </router-link>
          <button
            type="button"
            @click="deleteDesk(desk.id)"
            class="btn btn-danger"
          >
            Удалить
          </button>
        </div>
      </div>
    </div>
    <div class="alert alert-danger" role="alert" v-if="errored">
      A simple danger alert—check it out!
    </div>
    <div class="spinner-border" role="status" v-if="loading">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength } from "vuelidate/lib/validators";
export default {
  setup() {
    return { v$: useVuelidate() };
  },
  data() {
    return {
      desks: [],
      errored: false,
      loading: true,
      name: "",
      errors: [],
    };
  },
  mounted() {
    this.getDesks();
  },
  methods: {
    getDesks() {
      axios
        .get("/api/desks")
        .then((response) => {
          this.desks = response.data.data;
        })
        .catch((error) => {
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteDesk(id) {
      if (confirm("Удалить?")) {
        axios
          .post("/api/desks/" + id, {
            _method: "DELETE",
          })
          .then((response) => {
            this.desks = [];
            this.getDesks();
          })
          .catch((error) => {
            this.errored = true;
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    addNewDesk(id) {
      this.v$.$touch();
      if (this.v$.$anyError) {
        return;
      }
      axios
        .post("/api/desks/", {
          name: this.name,
        })
        .then((response) => {
          this.name = "";
          this.desks = [];
          this.getDesks();
        })
        .catch((error) => {
          if (error.response.data.errors.name) {
            this.errors = [];
            this.errors.push(error.response.data.errors.name)[0];
          }
          this.errored = true;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(5),
    },
  },
};
</script>