import TemplateComponent from "../../resources/js/components/TemplateComponent.vue";

import { mount } from "@vue/test-utils";
import expect from "expect";
import './fake-component-vuetify.js'

describe("TemplateComponent", () => {
    let component;

    beforeEach(() => {
        component = mount(TemplateComponent);
    });

    it("Contains TemplateComponent", () => {
        expect(component.html()).toContain(TemplateComponent);
    });
});
