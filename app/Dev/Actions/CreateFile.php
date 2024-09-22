<?php

declare(strict_types=1);

namespace App\Dev\Actions;

use App\Dev\Enums\DomainFileTypesEnum;
use App\Dev\Exceptions\InvalidParamsException;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateFile extends DevTool
{
    public function getCommandSignature(): string
    {
        return 'make:domain-file {domain} {type} {--filename=}';
    }

    public function getCommandDescription(): string
    {
        return 'Create a new file in the specified domain';
    }

    public function asCommand(Command $command): void
    {
        $this->handle([
            'domain' => $command->argument('domain'),
            'filename' => $command->argument('filename'),
            'type' => $command->argument('type')
        ]);
    }

    public function runDevTool(mixed $params = null): void
    {
        if (
            !array_key_exists('domain', $params) ||
            !array_key_exists('filename', $params) ||
            !array_key_exists('type', $params)
        )
            throw new InvalidParamsException();
        
        $domain = Str::singular(ucfirst($params['domain']));
        $pluralised_domain = Str::plural($domain);
        $filename = $params['filename'];
        $type = $params['type'];
        $path = "app/Domains/$pluralised_domain";

        if ($type === DomainFileTypesEnum::MODEL) {
            $path .= '';
        } elseif ($type === DomainFileTypesEnum::ENUM) {
            $path .= '/Enums';
        } else {
            $path .= '/Actions';
        }

        $content = $this->getFileContent($domain, $type);

        file_put_contents("$path/$filename", $content);
    }

    private function getFileContent(string $domain, string $type): string
    {
        switch ($type) {
            case DomainFileTypesEnum::CREATE_ACTION:
                return $this->getCreateActionContent($domain);
            case DomainFileTypesEnum::UPDATE_ACTION:
                return $this->getEditActionContent($domain);
            case DomainFileTypesEnum::TRASH_ACTION:
                return $this->getTrashActionContent($domain);
            case DomainFileTypesEnum::RESTORE_ACTION:
                return $this->getRestoreActionContent($domain);
            case DomainFileTypesEnum::DELETE_ACTION:
                dd('not implemented yet');
                // return $this->getCreateActionContent($domain);
            case DomainFileTypesEnum::ACTION:
                dd('not implemented yet');
                // return $this->getCreateActionContent($domain);
            case DomainFileTypesEnum::ENUM:
                dd('not implemented yet');
                // return $this->getCreateActionContent($domain);
            case DomainFileTypesEnum::MODEL:
                return $this->getModelContent($domain);
            default:
                throw new InvalidParamsException();
        }
    }

    private function getCreateActionContent(string $domain)
    {
        $domain = ucfirst($domain);
        $pluralised_domain = Str::plural($domain);
        $var_name = strtolower(Str::snake($domain));

        return "<?php

declare(strict_types=1);

namespace App\Domains\\$pluralised_domain\Actions;

use App\Domains\\$pluralised_domain\\$domain;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Create$domain
{
    use AsAction;

    public function authorize(ActionRequest \$request): Response
    {
        \$user = \$request->user();
        
        if (\$user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array \$params): $domain
    {
        return $domain::create(\$params);
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(ActionRequest \$request)
    {
        return \$this->handle(\$request->validated());
    }

    public function jsonResponse($domain \$$var_name): array
    {
        return [
            'message' => '$domain created successfully',
        ];
    }
}
";
    }

    private function getEditActionContent(string $domain): string
    {
        $domain = ucfirst($domain);
        $pluralised_domain = Str::plural($domain);
        $var_name = strtolower(Str::snake($domain));

        return "<?php

declare(strict_types=1);

namespace App\Domains\\$pluralised_domain\Actions;

use App\Domains\\$pluralised_domain\\$domain;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Update$domain
{
    use AsAction;

    public function authorize(ActionRequest \$request): Response
    {
        \$user = \$request->user();
        
        if (\$user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string \$id, array \$params): $domain
    {
        \$$var_name = $domain::findOrFail(\$id);
        \${$var_name}->update(\$params);

        return \$$var_name;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function asController(string \$id, ActionRequest \$request)
    {
        return \$this->handle(\$id, \$request->validated());
    }

    public function jsonResponse($domain \$$var_name): array
    {
        return [
            'message' => '$domain updated successfully',
        ];
    }
}
";
    }

    private function getTrashActionContent(string $domain): string
    {
        $domain = ucfirst($domain);
        $pluralised_domain = Str::plural($domain);
        $var_name = strtolower(Str::snake($domain));

        return "<?php

declare(strict_types=1);

namespace App\Domains\\$pluralised_domain\Actions;

use App\Domains\\$pluralised_domain\\$domain;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Trash$domain
{
    use AsAction;

    public function authorize(ActionRequest \$request): Response
    {
        \$user = \$request->user();
        
        if (\$user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(string \$id): bool
    {
        \$$var_name = $domain::find(\$id);

        return \${$var_name}->delete();
    }

    public function asController(string \$id)
    {
        return \$this->handle(\$id);
    }

    public function jsonResponse(bool \$deleted): array
    {
        \$success = \$deleted ? 'successful' : 'unsuccessful';

        return [
            'message' => \"$domain delete \$success\",
        ];
    }
}
";
    }

    private function getRestoreActionContent(string $domain)
    {
        $domain = ucfirst($domain);
        $pluralised_domain = Str::plural($domain);
        $var_name = strtolower(Str::snake($domain));

        return "<?php

declare(strict_types=1);

namespace App\Domains\\$pluralised_domain\Actions;

use App\Domains\\$pluralised_domain\\$domain;
use Illuminate\Auth\Access\Response;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class Restore$domain
{
    use AsAction;

    public function authorize(ActionRequest \$request): Response
    {
        \$user = \$request->user();
        
        if (\$user->has_general_access)
            return Response::allow();

        return Response::deny('You are unauthorised to perform this action');
    }

    public function handle(array \$params): $domain
    {
        \$$var_name = $domain::withTrashed()->where('id', \$params['id'])->first();
        \${$var_name}->restore();

        return \$$var_name;
    }

    public function rules(): array
    {
        return [
            'id' => ['exists:']
        ];
    }

    public function asController(ActionRequest \$request)
    {
        return \$this->handle(\$request->validated());
    }

    public function jsonResponse(bool \$restored): array
    {
        \$success = \$restored ? 'successful' : 'unsuccessful';

        return [
            'message' => \"$domain restoration was \$success\",
        ];
    }
}
";
    }

    private function getModelContent(string $domain)
    {
        $domain = ucfirst($domain);
        $pluralised_domain = Str::plural($domain);

        return "<?php

namespace App\Domains\\$pluralised_domain;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class $domain extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
}
";
    }
}
