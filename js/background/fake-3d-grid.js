class Point {
    _x;
    _y;

    get x() {
        return this._x;
    }
    get y() {
        return this._y;
    }

    constructor(x, y) {
        this._x = x; this._y = y;
    }
}

class Grid {
    field;
    numberOfRows;
    numberOfColumns;

    constructor(numberOfRows, numberOfColumns) {
        this.numberOfRows = numberOfRows;
        this.numberOfColumns = numberOfColumns;

        this.field = [];
        for (let i = 0; i < numberOfRows; i++) {
            if (i === 0) {
                this.field.push(this.createRow());
            } else {
                this.field.push(this.createRow(this.field[i - 1]));
            }
        }
    }

    // Configuration
    // Shortcut to apply different settings in 1 go: accepts a js object
    configure(settings) {
        this._keepInMemory = Object.hasOwn(settings, 'keepInMemory') ? settings.keepInMemory : this._keepInMemory;
        this._maxVariation = Object.hasOwn(settings, 'maxVariation') ? settings.maxVariation : this._maxVariation;
        this._lowerLimitVariation = Object.hasOwn(settings, 'lowerLimitVariation') ? settings.lowerLimitVariation : this._lowerLimitVariation;
        this._upperLimitVariation = Object.hasOwn(settings, 'upperLimitVariation') ? settings.upperLimitVariation : this._upperLimitVariation;
       
    }

    // OFFSET
    // Used to create scrolling animation
    _offset = 1;
    get offset() {
        return this._offset;
    }
    set offset(amount) {
        this._offset = amount;

        // correct offset to make it circular, adding new rows when necessary
        if (this._offset < 0) {
            this._offset = 1;

            let newRow = this.createRow(this.field[0]);
            this.field.unshift(newRow);

            if (!this._keepInMemory) this.field.pop();
        }
        if (this._offset > 1) {
            this._offset = 0;

            let newRow = this.createRow(this.field[this.field.length - 1]);
            this.field.push(newRow);

            if (!this._keepInMemory) this.field.shift();
        }
    }

    // KEEP ROWS IN MEMORY
    // If true, will infinitely add rows to the grid
    // If false, will remove rows when adding to keep a constant total number
    _keepInMemory = false;

    // TERRAIN GENERATION PARAMETERS
    // Max variation says how much a point can vary compared to neighbouring points
    _maxVariation = 0.09;
    // Lower and upper limits
    _lowerLimitVariation = -0.85;
    _upperLimitVariation = -0.2;

    // CREATE ROW
    // Takes another row as optional parameter to compare against (to create logically flowing terrain)
    createRow(compareToRow = []) {
        let row = [];
        for (let i = 0; i < this.numberOfColumns; i++) {
            let x = 0, y = 0;
            if (compareToRow.length === 0) {
                if (i === 0) {
                    y = this.randomCoord(this._lowerLimitVariation, this._upperLimitVariation);
                } else {
                    let proposedY = this.randomCoord(row[i - 1].y - this._maxVariation, row[i - 1].y + this._maxVariation);
                    y = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedY));
                }

            } else {
                if (i === 0) {
                    let proposal1 = this.randomCoord(this._lowerLimitVariation, this._upperLimitVariation);
                    let proposal2 = this.randomCoord(compareToRow[i].y - this._maxVariation, compareToRow[i].y + this._maxVariation);
                    let proposedY = (proposal1 + proposal2) * 0.5;
                    y = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedY));
                } else {
                    let proposal1 = this.randomCoord(compareToRow[i].y - this._maxVariation, compareToRow[i].y + this._maxVariation);
                    let proposal2 = this.randomCoord(row[i - 1].y - this._maxVariation, row[i - 1].y + this._maxVariation);
                    let proposedY = (proposal1 + proposal2) * 0.5;
                    y = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedY));
                }
            }
            row.push(new Point(x, y));
        }
        return row;
    }
    // Quick helper to get randomized coordinates for points
    randomCoord(min, max) {
        return Math.random() * (max - min) + min
    }

    // PERSPECTIVE MODIFIERS (Determine growth of values as row count increases, make closer squares appear larger)
    // 1. xMultiplier is multiplied with projectedX, becomes bigger incrementally
    // higher number means that the vanishing point will appear to be closer to the horizon
    _xMultiplier = 0.25;

    // 2. yMultiplier defines how quickly y increments as row count grows (increasing vertical distance between squares)
    _yMultiplier = 3.5;

    // 3. Multiplier for horizon curvature
    _xCurve = 0.5;

    // PROJECTION
    // Offsets grid to make 3D projection effect
    _projection;
    get projection() {
        this._projection = [];

        for (let i = 0; i < this.numberOfRows; i++) {
            let projectedRow = [];

            let projectionOffsetY = Math.pow(Math.max(i - this._offset, 0) / this.numberOfRows, 2);

            for (let j = 0; j < this.numberOfColumns; j++) {

                // PREPARE TERRAIN MODIFIERS
                let currentPoint = this.field[i][j],
                    terrainX = currentPoint.x,
                    terrainY = currentPoint.y;

                // PROJECTION OF X
                // 1. Project X from the middle
                // range should be -0.5 to 0.5, to be multiplied with width of the canvas when drawing
                let projectedX = (j - this.numberOfColumns * 0.5 + 0.5) * (1 / (this.numberOfColumns - 1));

                // 2. Make projection more pronounced the closer to the camera you are
                // also apply offset here
                // play with vanishing ratio to modify how pronounced it is (higher number should make the vanishing point appear closer to the horizon)
                projectedX *= (1 + (i - this._offset) * this._xMultiplier);

                let finalX = projectedX;

                // PROJECTION OF Y
                // 1. Increase Y as row count increases, and immediately process vertical offset
                let projectedY = (i - this._offset) / this.numberOfColumns;

                // 2. Make increments of Y larger as row count increases
                projectedY *= 1 + ((i - this._offset) / this.numberOfColumns) * this._yMultiplier;

                // 3. Add curve to the horizon
                projectedY += Math.pow(Math.abs(j - this.numberOfColumns * 0.5 + 0.5) * 0.005 * this._xCurve, 1.2);

                let finalY = projectedY + terrainY * (i - this._offset) / this.numberOfRows;

                projectedRow.push(new Point(finalX, finalY));

            }
            this._projection.push(projectedRow);
        }
        return this._projection;
    }

    // HORIZON
    // Draw a horizon between first and second row (used in animation)
    _horizon = [];
    get horizon() {
        this._horizon = [];
        for (let i = 0; i < this._projection[0].length; i++) {
            let previous = this._projection[0][i], next = this._projection[1][i];

            let horizonX = previous.x * (1 - this._offset) + next.x * this._offset,
                horizonY = previous.y * (1 - this._offset) + next.y * this._offset;
            this._horizon.push(new Point(horizonX, horizonY));
        }
        return this._horizon;
    }
}

