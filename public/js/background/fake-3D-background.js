class Point {
    _x;
    _y;
    _z;

    get x() {
        return this._x;
    }
    get y() {
        return this._y;
    }
    get z() {
        return this._z;
    }
   
    constructor(x, y, z = 0) {
        this._x = x; this._y = y; this._z = z;
    }
}

class Grid {

    // BASE PROPS
    grid;
    numberOfRows;
    numberOfColumns;

    // MAKE NEW GRID
    generateGrid(rows, columns) {
        this.numberOfRows = rows;
        this.numberOfColumns = columns;

        this.grid = [];
        for (let i = 0; i < this.numberOfRows; i++) {
            if (i === 0) {
                this.grid.push(this.createRow());
            } else {
                this.grid.push(this.createRow(this.grid[i - 1]));
            }
        }
        return this.grid;
    }

    // TERRAIN GENERATION PARAMETERS
    // Max variation says how much a point can vary compared to neighbouring points
    set terrainVariation(value){
        this._maxVariation = value / 400;
    }
    get terrainVariation(){
        return this._maxVariation;
    }
    _maxVariation = 0.2;

    // Lower and upper limits
    set elevationLimit(value) {
        this._lowerLimitVariation = -value / 100;
    }
    get elevationLimit() {
        return Math.abs(this._lowerLimitVariation);
    }
    _lowerLimitVariation = -0.5;
    _upperLimitVariation = 0;

    // CREATE ROW
    // Takes another row as optional parameter to compare against (to create logically flowing terrain)
    createRow(compareToRow = []) {
        let row = [];
        for (let i = 0; i < this.numberOfColumns; i++) {
            let x = 0, y = 0, z = 0;
            if (compareToRow.length === 0) {
                if (i === 0) {
                    z = this.randomCoord(this._lowerLimitVariation, this._upperLimitVariation);
                } else {
                    let proposedZ = this.randomCoord(row[i - 1].z - this._maxVariation, row[i - 1].z + this._maxVariation);
                    z = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedZ));
                }

            } else {
                if (i === 0) {
                    let proposal1 = this.randomCoord(this._lowerLimitVariation, this._upperLimitVariation);
                    let proposal2 = this.randomCoord(compareToRow[i].z - this._maxVariation, compareToRow[i].z + this._maxVariation);
                    let proposedZ = (proposal1 + proposal2) * 0.5;
                    z = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedZ));
                } else {
                    let proposal1 = this.randomCoord(compareToRow[i].z - this._maxVariation, compareToRow[i].z + this._maxVariation);
                    let proposal2 = this.randomCoord(row[i - 1].z - this._maxVariation, row[i - 1].z + this._maxVariation);
                    let proposedZ = (proposal1 + proposal2) * 0.5;
                    z = Math.min(this._upperLimitVariation, Math.max(this._lowerLimitVariation, proposedZ));
                }
            }
            row.push(new Point(x, y, z));
        }
        return row;
    }

    // HELPERS
    // get randomized coordinates for point
    randomCoord(min, max) {
        return Math.random() * (max - min) + min
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

            let newRow = this.createRow(this.grid[0]);
            this.grid.unshift(newRow);

            if (!this._keepInMemory) this.grid.pop();
        }
        if (this._offset > 1) {
            this._offset = 0;

            let newRow = this.createRow(this.grid[this.grid.length - 1]);
            this.grid.push(newRow);

            if (!this._keepInMemory) this.grid.shift();
        }
    }
}

class Projection extends Grid {
    // PROJECTION
    // Offsets grid to make 3D projection effect
    _projection;

    // SETTABLE PROPS
    // Horizon height: of 0, horizon starts at top of element; if 1, horizon starts at bottom
    set horizonHeight(value) {
        this._horizonHeight = 1 - value / 100;
    }
    get horizonHeight() {
        return 1 - this._horizonHeight;
    }
    _horizonHeight = 0.5;

    // Horizontal convergence: how quickly do vertical lines converge horizontally? 
    // Or how much wider do things appear closer to the bottom edge?
    set hConvergence(value) {
        this._hConvergence = value / 100;
    }
    get hConvergence() {
        return this._hConvergence;
    }
    _hConvergence = 0.25;

    // Vertical divergence: determines rate at which y becomes more extreme as row nears viewpont
    // Rows close to the horizon become smaller
    set vDivergence(value) {
        this._vDivergence = value / 100;
    }
    get vDivergence(){
        return this._vDivergence;
    }
    _vDivergence = 1.5;

    // Horizon curve: determines how much curve is visible in the horizon
    set horizonCurve(value) {
        this._horizonCurve = value / 100;
    }
    get horizonCurve(){
        return this._horizonCurve;
    }
    _horizonCurve = 0.25;

    // Main projection logic
    get projection() {
        this._projection = [];

        for (let i = 0; i < this.grid.length; i++) {
            let projectedRow = [];

            for (let j = 0; j < this.numberOfColumns; j++) {

                // BASE VALUES
                // X & Y go from 0 to 1, where 1 is total element width or height
                // Note: base ratio rows/columns on ratio element width/height to get approximately square grid
                let baseX = j / (this.numberOfColumns - 1),
                    baseY = i / (this.numberOfRows - 1),
                    correctedBaseY = baseY - this._offset / (this.numberOfRows - 1);

                // PROJECTION OF X
                // 1. Start from the base
                let projectedX = baseX;

                // 2. ProjectX from center, make it more pronounced the higher rowcount (i) becomes
                let projectFromCenter = (j - this.numberOfColumns * 0.5 + 0.5) / this.numberOfColumns;
                projectedX += projectFromCenter * (i - this._offset) * this._hConvergence;

                // 3. Apply terrain mod to get finalX
                let finalX = projectedX;

                // PROJECTION OF Y
                // 1. Start from the base
                let projectedY = baseY;

                // 2. Apply vertical offset (for animation)
                projectedY -= this._offset / (this.numberOfRows - 1);

                // 3. Make y more pronounced the higher the rowcount (i) becomes    
                projectedY = Math.pow(Math.max(projectedY, 0), this._vDivergence + correctedBaseY);

                // 4. Set horizon curve
                projectedY += Math.pow(Math.abs(projectFromCenter), 1.5) * this._horizonCurve;

                // 5. Add horizon offset and terrain mod to get finalY
                let finalY = this._horizonHeight + projectedY;

                let currentPoint = this._grid[i][j];
                finalY += currentPoint.z * correctedBaseY;
                                
                // RESULT
                projectedRow.push(new Point(finalX, finalY));

                // If last row contains point that's not at bottom of screen, generate extra row
                if (i == this.grid.length - 1 && finalY <= 1)
                    this.grid.push(this.createRow(this.grid[this.grid.length - 1]));
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

class Background extends Projection {
    // Main props
    _canvas;
    _grid;
    _width;
    _height;
    movementAllowed;

    // Draw props
    fillColor = "yellow";
    strokeColor = "black";
    strokeWidth = 1;

    // SETTER AND GETTERS
    // Size prop: determines amount of columns and rows
    _size = 50;
    get size() {
        return this._size / 2;
    }
    set size(value) {
        this._size = Math.max(10, value * 2);
    }

    // Constructor: set event listeners and generate first grid
    constructor(canvasId, movementAllowed = true) {
        super();

        this._canvas = document.getElementById(canvasId);
        this.movementAllowed = movementAllowed;

        // set event listeners
        window.addEventListener("resize", () => this.resize());
        window.addEventListener("keydown", (e) => this.setOffset(e));
        this.resize();
    }

    // Offset bindings
    changeOffset(offset) {
        this.offset -= offset;
        this.draw();
    }

    setOffset(e) {
        if (this.movementAllowed) {
            if (e.code == "ArrowUp") {
                this.offset -= 0.1;
            }
            if (e.code == "ArrowDown") {
                this.offset += 0.1;
            }
            this.draw();
        }
    }

    // Resize canvas: recalculate number of columns and rows, force regeneration of base grid and draw
    resize() {
        const rect = this._canvas.getBoundingClientRect();
        const cssWidth = rect.width; const cssHeight = rect.height;

        if (this._canvas.width != cssWidth || this._canvas.height != cssHeight) {
            this._canvas.width = cssWidth;
            this._width = cssWidth;
            this._canvas.height = cssHeight;
            this._height = cssHeight;

            let screenRatio = (cssHeight / cssWidth);

            let columns = this._size, rows = Math.round((columns * screenRatio) * 0.5) * 2;
            this._grid = this.generateGrid(rows, columns);
            this.draw();
        }
    }

    // Draw function: draws to the canvas
    draw() {
        const ctx = this._canvas.getContext("2d");

        if (ctx) {
            ctx.clearRect(0, 0, this._width, this._height);

            let projection = this.projection;
            let horizon = this.horizon;

            ctx.fillStyle = this.fillColor;
            ctx.strokeStyle = this.strokeColor;
            ctx.lineWidth = this.strokeWidth;

            // Create the actual grid
            for (let i = 0; i < projection.length; i++) {
            
                // Start by creating a polygon to be filled
                for (let j = 0; j < projection[i].length; j++) {

                    if (j === projection[i].length - 1) continue;

                    let topLeftPoint = (i === 0) ? horizon[j] : projection[i][j], 
                        topRightPoint = (i === 0) ? horizon[j + 1] : projection[i][j + 1];
                    
                    ctx.beginPath();
                    ctx.moveTo(topLeftPoint.x * this._width, topLeftPoint.y * this._height);
                    ctx.lineTo(topRightPoint.x * this._width, topRightPoint.y * this._height);

                    if (i < projection.length - 1) {
                        let bottomRightPoint = projection[i + 1][j + 1], bottomLeftPoint = projection[i + 1][j];
                        ctx.lineTo(bottomRightPoint.x * this._width, bottomRightPoint.y * this._height);
                        ctx.lineTo(bottomLeftPoint.x * this._width, bottomLeftPoint.y * this._height)
                    } else {
                        ctx.lineTo(topLeftPoint.x, this._height);
                    }
                    ctx.fill();
                    ctx.stroke();

                }

                // Next make a new path for the stroke
                // Horizontal line first
                ctx.beginPath();
                for (let j = 0; j < projection[i].length; j++) {
                    let point = (i === 0) ? horizon[j] : projection[i][j];
                    if (j === 0) {
                        ctx.moveTo(point.x * this._width, point.y * this._height);
                    } else {
                        ctx.lineTo(point.x * this._width, point.y * this._height);
                    }
                }
                // Vertical next
                if (i < projection.length - 1) {
                    for (let j = 0; j < projection[i].length; j++) {
                        let point = (i === 0) ? horizon[j] : projection[i][j], nextPoint = projection[i + 1][j]

                        ctx.moveTo(point.x * this._width, point.y * this._height);
                        ctx.lineTo(nextPoint.x * this._width, nextPoint.y * this._height);
                    }
                }
                ctx.stroke();
            }
        }
    }
}