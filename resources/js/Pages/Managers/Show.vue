<template>
    <div>
      <h1 class="text-2xl font-bold mb-4">Manager Details</h1>
      <div class="mb-4">
        <p><strong>Nombre:</strong> {{ manager.name }}</p>
        <p><strong>Correo Electrónico:</strong> {{ manager.email }}</p>
      </div>
      <div class="mt-4">
        <Link :href="`/managers/${manager.id}/edit`" class="btn btn-warning">Edit</Link>
        <Link href="/managers" class="btn btn-secondary">Back to List</Link>
      </div>
    </div>
  </template>

  <script setup>
  import { usePage } from '@inertiajs/vue3';


    const { props } = usePage();
    const manager = props.manager;

  </script>
