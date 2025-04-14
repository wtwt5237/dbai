import { Client } from "@gradio/client";

const app = Client.connect("http://127.0.0.1:7860/");

const app_info = await app.view_api();

console.log(app_info);
