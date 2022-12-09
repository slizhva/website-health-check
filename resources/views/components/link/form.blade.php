<form method="POST" action="{{ $action }}" class="row border p-3 mb-2">
    {{ csrf_field() }}

    @if (isset($link->id))<input name="id" type="number" value="{{ $link->id }}" hidden>@endif

    <label for="url">URL:</label>
    <input
        id="url"
        name="url"
        type="text"
        placeholder="https://google.com"
        class="col-md-12"
        required
        value="@if (isset($link->url)){{ $link->url }}@endif"
    >

    <label class="mt-2" for="success_content">Content to check:</label>
    <textarea
        id="success_content"
        name="success_content"
        type="text"
        placeholder="hello world"
        rows="4"
        class="col-md-12"
        required
    >@if (isset($link->success_content)){{ $link->success_content }}@endif</textarea>

    <fieldset class="border p-2 mt-3">
        <legend class="float-none w-auto h5 m-0">On ERROR</legend>

        <label for="requestErrorUrl">Request url:</label>
        <input
            id="requestErrorUrl"
            name="request_error_url"
            type="text"
            placeholder="https://google.com"
            class="col-md-12"
            value="@if (isset($link->error_command->url)){{ $link->error_command->url }}@endif"
        >

        <label class="mt-2" for="requestErrorType">Request type:</label>
        <input
            id="requestErrorType"
            name="request_error_type"
            type="text"
            placeholder="POST"
            class="col-md-12"
            value="@if (isset($link->error_command->type)){{ $link->error_command->type }}@endif"
        >

        <label class="mt-2"  for="requestErrorHeader">Request header:</label>
        <textarea
            id="requestErrorHeader"
            name="request_error_header"
            type="text"
            placeholder="Content-Type: application/json;charset=UTF-8"
            class="col-md-12"
            rows="4"
        >@if (isset($link->error_command->header)){{ $link->error_command->header }}@endif</textarea>
    </fieldset>

    <input type="submit" value="{{ $buttonText }}" class="col-md-2 mt-3">
</form>
