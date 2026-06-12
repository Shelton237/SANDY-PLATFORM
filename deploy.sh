#!/usr/bin/env python3
"""Deploy Sandy Platform to production server via SSH."""

import sys
import io
import paramiko

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8', errors='replace')
sys.stderr = io.TextIOWrapper(sys.stderr.buffer, encoding='utf-8', errors='replace')

HOST = "38.242.223.70"
USER = "root"
PASS = "tTW4vLpR3KY5Led7M3tu"
DIR  = "/var/www/sandyjuice"

COMMANDS = [
    f"git config --global --add safe.directory {DIR}",
    f"cd {DIR} && git fetch origin main",
    f"cd {DIR} && git reset --hard origin/main",
    f"cd {DIR} && git clean -fd",
    f"cd {DIR} && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts",
    f"cd {DIR} && php artisan package:discover --ansi",
    f"cd {DIR} && npm ci --prefer-offline",
    f"cd {DIR} && npm run build",
    f"cd {DIR} && php artisan migrate --force",
    f"cd {DIR} && php artisan storage:link --force",
    f"chown -R www-data:www-data {DIR}",
    f"chmod -R 755 {DIR}",
    f"chmod -R 775 {DIR}/storage {DIR}/bootstrap/cache",
    f"sudo -u www-data php {DIR}/artisan config:cache",
    f"sudo -u www-data php {DIR}/artisan route:cache",
    f"sudo -u www-data php {DIR}/artisan view:cache",
    f"sudo -u www-data php {DIR}/artisan event:cache",
    f"sudo -u www-data php {DIR}/artisan queue:restart",
    "systemctl reload php8.3-fpm",
]


def run():
    client = paramiko.SSHClient()
    client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    print(f"Connexion a {HOST}...")
    client.connect(HOST, username=USER, password=PASS, timeout=30)

    ok = True
    for cmd in COMMANDS:
        print(f"\n$ {cmd}")
        _, stdout, stderr = client.exec_command(cmd, timeout=300)
        out = stdout.read().decode('utf-8', errors='replace')
        err = stderr.read().decode('utf-8', errors='replace')
        exit_code = stdout.channel.recv_exit_status()
        if out:
            print(out.rstrip())
        if err:
            print(err.rstrip(), file=sys.stderr)
        if exit_code != 0:
            print(f"[ERREUR] exit code {exit_code}", file=sys.stderr)
            ok = False
            break

    client.close()
    if ok:
        print("\nDeploy termine -- https://sandyjuice.cm")
    else:
        print("\nDeploy echoue", file=sys.stderr)
        sys.exit(1)


if __name__ == "__main__":
    run()
