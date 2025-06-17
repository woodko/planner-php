document.addEventListener('DOMContentLoaded', () => {
  // Колонки — контейнеры с классом .scrum-column
  const columns = document.querySelectorAll('.scrum-column');
  // Задачи — элементы с классом .task
  const tasks = document.querySelectorAll('.task');

  // DRAG START
  tasks.forEach(task => {
    task.setAttribute('draggable', true);
    task.addEventListener('dragstart', e => {
      e.dataTransfer.setData('text/plain', task.dataset.id);
    });

    // INLINE EDIT
    const titleEl = task.querySelector('.task-title');
    titleEl.addEventListener('dblclick', () => {
      const old = titleEl.textContent.trim();
      const input = document.createElement('input');
      input.value = old;
      input.classList.add('form-control');
      titleEl.replaceWith(input);
      input.focus();

      input.addEventListener('blur', () => {
        const val = input.value.trim();
        input.replaceWith(titleEl);
        titleEl.textContent = old;
        if (val && val !== old) {
          fetch('update_title.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
              task_id: task.dataset.id,
              title: val
            })
          })
          .then(r => r.json())
          .then(json => {
            if (json.success) titleEl.textContent = val;
            else alert(json.error || 'Ошибка обновления');
          })
          .catch(() => alert('Ошибка сети'));
        }
      });
    });
  });

  // DROP & UPDATE STATUS
  columns.forEach(col => {
    col.addEventListener('dragover', e => {
      e.preventDefault();
      col.classList.add('drag-over');
    });
    col.addEventListener('dragleave', () => {
      col.classList.remove('drag-over');
    });
    col.addEventListener('drop', e => {
      e.preventDefault();
      col.classList.remove('drag-over');
      const id = e.dataTransfer.getData('text/plain');
      const task = document.querySelector(`.task[data-id="${id}"]`);
      if (!task) return;
      // Вложенный container называется .tasks
      col.querySelector('.tasks').appendChild(task);

      fetch('update_status.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          task_id: id,
          status: col.dataset.status
        })
      })
      .then(r => r.json())
      .then(json => {
        if (!json.success) alert(json.error || 'Ошибка статуса');
      })
      .catch(() => alert('Ошибка сети'));
    });
  });
});
