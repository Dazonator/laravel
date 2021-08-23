
<form class="col add-task">
    <div class="bg p-4 mb-4">
        <div class="mb-3">
            <label class="form-label">Название</label>
            <input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Описание задачи</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="exampleDataList" class="form-label">Исполнитель</label>
            <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Нажмите для поиска...">
            <datalist id="datalistOptions">
                <option value="San Francisco">
                <option value="New York">
                <option value="Seattle">
                <option value="Los Angeles">
                <option value="Chicago">
            </datalist>
        </div>
        <div class="form-group">
            <label for="">Example select</label>
            <select class="custom-select">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
    </div>
    <button class="btn btn-success">добавить задачу</button>
</form>
