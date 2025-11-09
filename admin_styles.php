<style>
/* Toggle Switch CSS */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
}
input:checked + .slider {
  background-color: var(--primary-color);
}
input:focus + .slider {
  box-shadow: 0 0 1px var(--primary-color);
}
input:checked + .slider:before {
  transform: translateX(26px);
}
.slider.round {
  border-radius: 34px;
}
.slider.round:before {
  border-radius: 50%;
}

/* General Admin Styles */
:root {
    --primary-color: #FFD700;
    --secondary-color: #000000;
    --text-color: #333333;
    --light-gray: #f8f9fa;
    --dark-gray: #6c757d;
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    color: var(--text-color);
    background-color: #fff;
}

.dashboard-container {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 250px;
    background-color: var(--secondary-color);
    color: #fff;
    padding: 2rem 0;
    position: fixed;
    height: 100%;
}

.sidebar-header {
    padding: 0 1.5rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h2 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #fff;
}

.sidebar-header p {
    font-size: 0.9rem;
    opacity: 0.8;
}

.sidebar-menu {
    list-style: none;
    margin-top: 1rem;
}

.sidebar-menu li {
    margin-bottom: 0.5rem;
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.8rem 1.5rem;
    color: #fff;
    transition: var(--transition);
    text-decoration: none;
}

.sidebar-menu a:hover, .sidebar-menu a.active {
    background-color: var(--primary-color);
    color: var(--secondary-color);
}

.main-content {
    flex: 1;
    padding: 2rem;
    background-color: var(--light-gray);
    margin-left: 250px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.content-header h1 {
    font-size: 1.8rem;
    color: var(--secondary-color);
}

.btn, .logout-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: var(--primary-color);
    color: var(--secondary-color);
    font-weight: 600;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
    font-size: 1rem;
    text-decoration: none;
}

.btn:hover, .logout-btn:hover {
    background-color: var(--secondary-color);
    color: var(--primary-color);
}

.logout-btn {
    background-color: var(--secondary-color);
    color: #fff;
}

.btn-danger { background-color: #dc3545; color: #fff; }
.btn-danger:hover { background-color: #c82333; }
.btn-secondary { background-color: var(--dark-gray); color: #fff; }
.btn-secondary:hover { background-color: var(--secondary-color); }

.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 1.2rem;
    color: var(--secondary-color);
}

.card-body {
    padding: 1.5rem;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 0.8rem;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.table th {
    font-weight: 600;
    color: var(--secondary-color);
}

.table-actions { display: flex; gap: 0.5rem; }
.table-actions a, .table-actions button {
    color: var(--secondary-color);
    background: none;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    font-size: 1rem;
    padding: 5px;
}
.table-actions a:hover, .table-actions button:hover { color: var(--primary-color); }

.alert { padding: 1rem; margin-bottom: 1rem; border-radius: 5px; }
.alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

.empty-state { text-align: center; padding: 2rem; color: var(--dark-gray); }
.empty-state i { font-size: 3rem; margin-bottom: 1rem; color: var(--primary-color); }

.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: inherit;
    transition: var(--transition);
}
.form-control:focus { outline: none; border-color: var(--primary-color); }
textarea.form-control { resize: vertical; min-height: 100px; }

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.stat-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}
.stat-icon {
    font-size: 2rem;
    color: var(--primary-color);
    background-color: var(--light-gray);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.stat-info h4 { font-size: 1rem; color: var(--dark-gray); margin-bottom: 0.5rem; }
.stat-info p { font-size: 1.8rem; font-weight: 700; color: var(--secondary-color); margin: 0; }

@media (max-width: 768px) {
    .sidebar { position: static; width: 100%; height: auto; }
    .main-content { margin-left: 0; }
    .content-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
}

/* Pagination Styles */
.pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
}
.pagination a {
    color: var(--secondary-color);
    padding: 8px 12px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid #ddd;
    border-radius: 5px;
}
.pagination a:hover {
    background-color: var(--light-gray);
}
.pagination a.active {
    background-color: var(--primary-color);
    color: var(--secondary-color);
    border-color: var(--primary-color);
}
</style>