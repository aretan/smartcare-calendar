lock "~> 3.11.2"

set :application, "smartcare-calendar"
set :repo_url, "git@github.com:aretan/smartcare-calendar.git"
set :branch, ENV["branch"] || "master"
set :deploy_to, "/var/www/smartcare-calendar/#{fetch(:branch).tr("/", "-")}"

set :file_permissions_paths, ["application/writable/debugbar", "application/writable/cache", "application/writable/uploads", "application/writable/logs", "application/writable/session"]
set :file_permissions_users, ["www-data"]
before "deploy:updated", "deploy:set_permissions:acl"
set :keep_releases, 1

set :ssh_options, {
  keys: %w(~/.ssh/develop.pem),
  forward_agent: true,
  auth_methods: %w(publickey)
}

namespace :deploy do
  task :application_env_file do
    on roles(:web) do
      execute "ln -fns /var/www/smartcare-calendar/application/.env #{release_path}/application/.env"
    end
  end
end

after 'deploy:updated', 'deploy:application_env_file'
