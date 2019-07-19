import TemplateComponent from "../../resources/js/components/TemplateComponent";

import { mount } from "@vue/test-utils";
import expect from "expect";
// import Vuetify from 'vuetify';
// var Vue = require("vue");
// Vue.use(Vuetify);
describe("TemplateComponent", () => {
    let component;

    beforeEach(() => {
        component = mount(TemplateComponent);
    });

    it("Contains TemplateComponent", () => {
        expect(component.html()).toContain(TemplateComponent);
    });
});
