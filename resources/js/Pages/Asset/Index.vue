<script setup>
import { ref, computed } from "vue";
import { Head } from "@inertiajs/vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import Banner from "@/Components/Banner.vue";
import BreadcrumbHeader from "@/Components/BreadcrumbHeader.vue";
import HeaderTitle from "@/Components/HeaderTitle.vue";

import { Button } from "@/Components/ui/button";
import { Card, CardHeader } from "@/Components/ui/card";
import { IconCategory, IconPlus } from "@tabler/icons-vue";

import AssetTabs from "@/Components/Asset/AssetTabs.vue";
import AssetTab from "./Tabs/AssetTab.vue";
import CategoryTab from "./Tabs/CategoryTab.vue";

// Modal
import CreateModalCategory from "./Category/Create.vue";
import CreateModalAsset from "./Create.vue";
import CreateModalLocation from "./Location/Create.vue";
import LocationTab from "./Tabs/LocationTab.vue";

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    assets: {
        type: Object,
        default: () => ({
            data: [],
            meta: {},
            links: [],
        }),
    },

    categories: {
        type: Array,
        default: () => [],
    },

    locations: {
        type: Array,
        default: () => [],
    },

    pageSettings: {
        type: Object,
        required: true,
    },

    items: {
        type: Array,
        default: () => [],
    },

    state: {
        type: Object,
        default: () => ({}),
    },
});

const categories = computed(() => props.categories?.data ?? []);
const locations = computed(() => props.locations?.data ?? []);
const activeTab = ref("asset");

const showCategoryModal = ref(false);
const categoryMode = ref("create");
const selectedCategory = ref(null);

const openCategoryModal = () => {
    categoryMode.value = "create";
    selectedCategory.value = null;
    showCategoryModal.value = true;
};

const editCategory = (category) => {
    categoryMode.value = "edit";
    selectedCategory.value = category;
    showCategoryModal.value = true;
};

const showAssetModal = ref(false);
const assetMode = ref("create");
const selectedAsset = ref(null);

const openAssetModal = () => {
    assetMode.value = "create";
    selectedAsset.value = null;
    showAssetModal.value = true;
};

const editAsset = (asset) => {
    assetMode.value = "edit";
    selectedAsset.value = asset;
    showAssetModal.value = true;
};

// Location
const showLocationModal = ref(false);
const locationMode = ref("create");
const selectedLocation = ref(null);

const openLocationModal = () => {
    locationMode.value = "create";
    selectedLocation.value = null;
    showLocationModal.value = true;
};

const editLocation = (location) => {
    locationMode.value = "edit";
    selectedLocation.value = location;
    showLocationModal.value = true;
};
</script>

<template>
    <div class="flex w-full flex-col gap-y-6 pb-32">
        <Head title="Asset Management" />

        <!-- Breadcrumb -->
        <BreadcrumbHeader :items="props.items" />

        <!-- Banner -->
        <Banner
            :title="props.pageSettings.banner.title"
            :sub-title="props.pageSettings.banner.subtitle"
        />

        <!-- Main Card -->
        <Card class="overflow-hidden">
            <CardHeader class="p-0">
                <!-- Header -->
                <div
                    class="flex flex-col items-start justify-between gap-y-4 p-4 lg:flex-row lg:items-center"
                >
                    <HeaderTitle
                        :title="props.pageSettings.title"
                        :sub-title="props.pageSettings.subtitle"
                        :icon="IconCategory"
                    />

                    <!-- Tombol Tambah Aset -->
                    <Button
                        v-if="activeTab === 'asset'"
                        variant="cyan"
                        size="xl"
                        @click="openAssetModal"
                    >
                        <IconPlus class="size-4" />
                        Tambah Aset
                    </Button>

                    <!-- Tombol Tambah Kategori -->
                    <Button
                        v-else-if="activeTab === 'category'"
                        variant="cyan"
                        size="xl"
                        @click="openCategoryModal"
                    >
                        <IconPlus class="size-4" />
                        Tambah Kategori
                    </Button>

                    <!-- Tombol Tambah Kategori -->
                    <Button
                        v-else-if="activeTab === 'location'"
                        variant="cyan"
                        size="xl"
                        @click="openLocationModal"
                    >
                        <IconPlus class="size-4" />
                        Tambah Location
                    </Button>
                </div>

                <!-- Navigasi Tabs -->
                <div class="px-4 pb-3">
                    <AssetTabs v-model="activeTab" />
                </div>
            </CardHeader>

            <!-- Daftar Aset -->
            <AssetTab
                v-if="activeTab === 'asset'"
                :assets="props.assets"
                :categories="categories"
                :state="props.state"
                @edit="editAsset"
            />

            <!-- Kategori Aset -->
            <CategoryTab
                v-else-if="activeTab === 'category'"
                :categories="props.categories"
                :state="props.state"
                @edit="editCategory"
            />

            <!-- Kategori Aset -->
            <LocationTab
                v-else-if="activeTab === 'location'"
                :locations="props.locations"
                :state="props.state"
                @edit="editLocation"
            />

            <!-- Tab lainnya -->
            <div v-else class="p-10 text-center text-muted-foreground">
                Halaman tab ini akan kita buat selanjutnya.
            </div>
        </Card>

        <!-- ================================== -->
        <!-- MODALS -->
        <!-- ================================== -->

        <!-- Modal Tambah Asset -->
        <CreateModalAsset
            v-model:open="showAssetModal"
            :mode="assetMode"
            :asset="selectedAsset"
            :categories="categories"
        />

        <!-- Modal Tambah Kategori -->
        <CreateModalCategory
            v-model:open="showCategoryModal"
            :mode="categoryMode"
            :category="selectedCategory"
        />

        <CreateModalLocation
            v-model:open="showLocationModal"
            :mode="locationMode"
            :location="selectedLocation"
        />
    </div>
</template>
