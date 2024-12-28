<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">Residence Details</h1>
      <div class="mb-4">
        <p><strong>Name:</strong> {{ residence.name }}</p>
        <p><strong>Address:</strong> {{ residence.address }}</p>
        <p><strong>Manager:</strong> {{ residence.user.name }} ({{ residence.user.email }})</p>
      </div>
      <div>
        <h2 class="text-xl font-bold mb-2">Buildings</h2>
        <ul>
          <li v-for="building in residence.buildings" :key="building.id">
            {{ building.name }}
          </li>
        </ul>
      </div>
      <div class="mt-4">
        <Link :href="`/residences/${residence.id}/edit`" class="btn btn-warning">Edit</Link>
        <Link href="/residences" class="btn btn-secondary">Back to List</Link>
      </div>
    </div>
  </template>

  <script>
  import { usePage } from '@inertiajs/vue3';

  export default {
    setup() {
      const { props } = usePage();
      const residence = props.residence;

      return { residence };
    },
  };
  </script>
