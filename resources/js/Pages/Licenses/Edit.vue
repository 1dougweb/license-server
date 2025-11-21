<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    license: Object,
    products: Array,
});

const form = useForm({
    product_id: props.license.product_id,
    allowed_domain: props.license.allowed_domain || '',
    device_limit: props.license.device_limit,
    expires_at: props.license.expires_at ? new Date(props.license.expires_at).toISOString().slice(0, 16) : '',
});

const submit = () => {
    form.put(route('licenses.update', props.license.id));
};
</script>

<template>
    <Head title="Editar Licença" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Licença
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Token:</p>
                        <p class="font-mono text-xs break-all">{{ license.token }}</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <Select
                            v-model="form.product_id"
                            label="Produto"
                            :options="products.map(p => ({ value: p.id, label: p.name }))"
                            required
                            :error="form.errors.product_id"
                        />
                        <Input
                            v-model="form.allowed_domain"
                            label="Domínio Permitido (opcional)"
                            :error="form.errors.allowed_domain"
                        />
                        <Input
                            v-model="form.device_limit"
                            label="Limite de Dispositivos"
                            type="number"
                            required
                            :error="form.errors.device_limit"
                        />
                        <Input
                            v-model="form.expires_at"
                            label="Data de Expiração (opcional)"
                            type="datetime-local"
                            :error="form.errors.expires_at"
                        />

                        <div class="flex justify-end space-x-3">
                            <Link :href="route('licenses.index')">
                                <Button type="button" variant="secondary">Cancelar</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                Atualizar
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

