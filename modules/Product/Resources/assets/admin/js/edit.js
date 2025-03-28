import Vue from "vue";
import ProductMixin from "./mixins/ProductMixin";
import Errors from "@admin/js/Errors";

Vue.prototype.defaultCurrencySymbol = Ecommerce.defaultCurrencySymbol;
Vue.prototype.route = route;

new Vue({
    el: "#app",

    mixins: [ProductMixin],

    data: {
        formSubmissionType: null,
        form: {
            brand_id: '',
            name: '',
            description: '',
            short_description: '',
            price: '',
            special_price: '',
            special_price_type: '',
            special_price_start: '',
            special_price_end: '',
            selling_price: '',
            sku: '',
            manage_stock: 0,
            qty: '',
            in_stock: 1,
            is_active: true,
            new_from: '',
            new_to: '',
            variations: [],
            variants: [],
        },
        errors: new Errors(),
        selectizeConfig: {
            plugins: ["remove_button"],
        },
        searchableSelectizeConfig: {},
        categoriesSelectizeConfig: {},
        flatPickrConfig: {
            mode: "single",
            enableTime: true,
            altInput: true,
        },
    },

    created() {
        console.log(this.form);

        this.setFormData();
        this.setSearchableSelectizeConfig();
        this.setCategoriesSelectizeConfig();
        this.setDefaultVariantUid();
        this.setVariantsLength();
    },

    mounted() {
        this.hideAlertExitFlash();
    },

    methods: {
        prepareFormData(formData) {
            this.prepareVariations(formData.variations);
            this.prepareVariants(formData.variants);
            console.log("Dữ liệu sau khi chuẩn bị:", formData);
            return formData;
        },

        setFormData() {
            this.form = { ...this.prepareFormData(Ecommerce.data["product"]) };
        },

        setDefaultVariantUid() {
            if (this.hasAnyVariant) {
                this.defaultVariantUid = this.form.variants.find(
                    ({ is_default }) => is_default === true
                ).uid;
            }
        },

        setVariantsLength() {
            this.variantsLength = this.form.variants.length;
        },

        hideAlertExitFlash() {
            const alertExitFlash = $(".alert-exit-flash");

            if (alertExitFlash.length !== 0) {
                setTimeout(() => {
                    alertExitFlash.remove();
                }, 3000);
            }
        },

        submit({ submissionType }) {
            this.formSubmissionType = submissionType;
        },
    },
});
