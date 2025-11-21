<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    products: Array,
});

const form = useForm({
    product_id: '',
    allowed_domain: '',
    device_limit: 1,
    expires_at: '',
});

const submit = () => {
    form.post(route('licenses.store'));
};
</script>

<template>
    <Head title="Criar Licença" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar Licença
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
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
                            placeholder="Deixe vazio para permitir qualquer domínio. Ex: erp.test, localhost"
                            :error="form.errors.allowed_domain"
                        />
                        <p class="text-sm text-gray-500">
                            Deixe vazio para permitir qualquer domínio. Para desenvolvimento local, use: <code class="bg-gray-100 px-1 rounded">localhost</code> ou <code class="bg-gray-100 px-1 rounded">erp.test</code>
                        </p>
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
                                Criar
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

