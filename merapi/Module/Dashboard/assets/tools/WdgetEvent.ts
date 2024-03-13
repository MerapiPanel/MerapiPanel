/**
 * description: The Wdget class is used to create a widget for the dashboard. It provides methods for adding, removing, and rendering blocks and containers.
 * author       Il4mb <https://github.com/Il4mb>
 * date         2022-11-01
 * version      1.0.0 
 * @copyright   Copyright (c) 2022 MerapiPanel
 * @license     https://github.com/MerapiPanel/MerapiPanel/blob/main/LICENSE
 */

import { WdgetEntity } from "./WdgetEntity"


type Wdget_EventCoordinate = {
    x: number,
    y: number
}

class Wdget_EventSource {
    el: HTMLElement
    entity: WdgetEntity

    constructor(el: HTMLElement, entity: WdgetEntity) {
        this.el = el
        this.entity = entity
    }
}

class Wdget_EventMovingData {
    index: number
    position: "before" | "after" | null

    constructor(index: number, position: "before" | "after" | null) {
        this.index = index
        this.position = position
    }

}




class Wdget_EventDragStart {
    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string
    constructor(source: Wdget_EventSource, coordinate: Wdget_EventCoordinate) {
        this.coordinate = coordinate
        this.source = source
        this.type = "drag:start"
    }
}

class Wdget_EventDragStop {
    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string
    constructor(source: Wdget_EventSource, coordinate: Wdget_EventCoordinate) {
        this.coordinate = coordinate
        this.source = source
        this.type = "drag:start"
    }
}




class Wdget_EventDraggingIn {
    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string
    target: HTMLElement
    constructor(source: Wdget_EventSource, target: HTMLElement, coordinate: Wdget_EventCoordinate) {
        this.coordinate = coordinate
        this.source = source
        this.target = target
        this.type = "dragging:in"
    }
}

class Wdget_EventDraggingOut {
    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string

    constructor(source: Wdget_EventSource, coordinate: Wdget_EventCoordinate) {
        this.coordinate = coordinate
        this.source = source
        this.type = "dragging:out"
    }
}

class Wdget_EventDraggingMove {
    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string
    target: HTMLElement
    constructor(source: Wdget_EventSource, target: HTMLElement, coordinate: Wdget_EventCoordinate) {
        this.coordinate = coordinate
        this.source = source
        this.target = target
        this.type = "dragging:move"
    }
}




class Wdget_EventDrop {

    coordinate: Wdget_EventCoordinate
    source: Wdget_EventSource
    type: string
    index: number
    target: HTMLElement

    constructor(source: Wdget_EventSource, coordinate: Wdget_EventCoordinate, index: number, target: HTMLElement) {
        this.coordinate = coordinate
        this.source = source
        this.type = "drop"
        this.index = index
        this.target = target
    }
}




export {
    Wdget_EventDragStart,
    Wdget_EventDragStop,
    Wdget_EventDraggingMove,
    Wdget_EventDraggingIn,
    Wdget_EventDraggingOut,
    Wdget_EventDrop,
    Wdget_EventSource,
    Wdget_EventCoordinate,
    Wdget_EventMovingData
}