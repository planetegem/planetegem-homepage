// Orchestrator handles loading and switching between major animation scenes
// Central builder blocks subscribe to the orchestrator to listen for its events
class Orchestrator {    
    constructor() {
        // application starts in the loading state
        this.loading = true;
        // meaning interaction is blocked
        this.interactionAllowed = false;

        // Objects that subscribed to events
        this.listeners = new Map();
    }

    // subscribe to events (performed by other classes)
    subscribe(event, callback) {
        if (!this.listeners.has(event)) {
            this.listeners.set(event, []);
        }
        this.listeners.get(event).push(callback);
    }

    // unsubscribe from events (performed by other classes)
    unsubscribe(event, callback) {
        if (!this.listeners.has(event)) return;
        this.listeners.set(event, this.listeners.get(event).filter(cb => cb !== callback));
    }

    // signal that event has been triggered
    signal(event, data) {
        if (this.listeners.has(event))
            this.listeners.get(event).forEach(callback => callback(data));
    }

    // EVENT TRIGGERS
    // Signal that asset loading has completed
    loaded() {
        const oldState = this.loading;
        this.loading = false;
        const newState = this.loading;
        this.signal("loading", {oldState, newState});
    }

    // Signal that there is a change in interactabilityness
    interactable(interactionAllowed) {
        if (this.interactionAllowed == interactionAllowed) return;
        const oldState = this.interactionAllowed;
        const newState = interactionAllowed;
        this.interactionAllowed = interactionAllowed;
        this.signal("interactable", {oldState, newState});
    }


}

// JS Singleton pattern: instantiate the class once and always return the same instance
export const orchestrator = new Orchestrator();