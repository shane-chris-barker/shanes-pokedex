import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['results'];

    submit(event) {
        event.preventDefault();
        const form = event.currentTarget;
        fetch(form.action, {
            method: form.method,
            body: new FormData(form)
        }).then(response => response.json())
            .then(data => {
                this.resultsTarget.innerHTML = data.html
            })
    }
}
