<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">Create Residence</h1>
      <form @submit.prevent="submit">
        <div>
          <label for="name">Name</label>
          <input v-model="form.name" type="text" id="name" class="form-input" />
        </div>
        <div>
          <label for="address">Address</label>
          <textarea v-model="form.address" id="address" class="form-textarea"></textarea>
        </div>
        <div>
          <label for="user-name">Manager Name</label>
          <input v-model="form.user.name" type="text" id="user-name" class="form-input" />
        </div>
        <div>
          <label for="user-email">Manager Email</label>
          <input v-model="form.user.email" type="email" id="user-email" class="form-input" />
        </div>
        <div>
          <label for="user-password">Manager Password</label>
          <input v-model="form.user.password" type="password" id="user-password" class="form-input" />
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
      </form>
    </div>
  </template>

  <script>
  import { useForm } from '@inertiajs/vue3';

  export default {
    setup() {
      const form = useForm({
        name: '',
        address: '',
        user: {
          name: '',
          email: '',
          password: '',
        },
      });

      const submit = () => {
        form.post('/residences', {
          onSuccess: () => form.reset(),
        });
      };

      return { form, submit };
    },
  };
  </script>
