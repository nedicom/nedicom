<template>
  <div class="">
    <div v-if="editor" class="flex mt-5 border rounded-top p-3">
      <div @click="editor.chain().focus().toggleBold().run()"
        :disabled="!editor.can().chain().focus().toggleBold().run()" :class="{ 'bg-gray-100': editor.isActive('bold') }"
        class="text-gray-1000 bg-white  focus:outline-none hover:underline  focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
        Жирный
      </div>
      <div
        @click="editor.chain().focus().toggleHeading({ level: 3 }).updateAttributes('heading', { color: 'pink' }).run()"
        :class="{ 'bg-gray-100': editor.isActive('heading', { level: 3 }) }" class="
      text-gray-1000 bg-white  focus:outline-none hover:underline focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700
      ">
        Заголовок
      </div>
      <div @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'bg-gray-100': editor.isActive('bulletList') }" class="
      text-gray-1000 bg-white  focus:outline-none hover:underline focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700
       ">
        Список
      </div>

      <div @click="editor.chain().focus()
        .toggleBlockquote()
        .selectParentNode()
        .setImage({ src: 'https://nedicom.ru/' + auth.avatar_path, alt: auth.name, title: auth.name, })
        .selectParentNode()
        .setLink({ href: 'https://nedicom.ru/lawyers' + auth.avatar_path, })
        .run()
        " class="
      text-gray-1000 bg-white  focus:outline-none hover:underline focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700
       ">
        Мнение эксперта
      </div>
    </div>
    <editor-content :editor="editor" name="body" class="form-control
              overflow-auto                  
              text-base
              font-normal
              text-gray-700                  
              rounded-bottom                 
              ease-in-out                
              border                
      " required />

  </div>
</template>

<script>

import Image from '@tiptap/extension-image'
import Blockquote from '@tiptap/extension-blockquote'
import StarterKit from '@tiptap/starter-kit'
import { Editor, EditorContent } from '@tiptap/vue-3'
import Link from '@tiptap/extension-link'

export default {
  components: {
    EditorContent,
  },

  props: {
    auth: Object,
    modelValue: {
      type: String,
      default: '',
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      editor: null,
    }
  },

  watch: {
    modelValue(value) {
      // HTML
      const isSame = this.editor.getHTML() === value

      // JSON
      // const isSame = JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)
      if (isSame) {
        return
      }

      this.editor.commands.setContent(value, false)
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit,
        Blockquote,
        Image.configure({
          inline: true,
          HTMLAttributes: {
            class: 'inline-flex mr-4 artlwrimg',
          },
        }),
        Link.configure({
          openOnClick: false,
          defaultProtocol: 'https',
          HTMLAttributes: {
            class: 'border-none artlwrhref',
          },
        }),
      ],
      content: this.modelValue,
      onUpdate: () => {
        // HTML
        this.$emit('update:modelValue', this.editor.getHTML())

        // JSON
        // this.$emit('update:modelValue', this.editor.getJSON())
      },
    })
  },

  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>

<style lang="scss">
/* Basic editor styles */

.ProseMirror-focused {
  outline: none;
}

.ProseMirror {
  height: 400px;
  padding: 10px;

  >*+* {
    // margin-top: 0.75em;

  }

  p {
    border: none;
  }

  ul,
  ol {
    padding: 0 2rem;
    margin: 1rem 0;
    list-style-type: square;
  }

  h3 {
    line-height: 1.3;
    font-size: 1.5rem;
    border: none;
    margin: 1rem;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0D0D0D;
    color: #FFF;
    font-family: 'JetBrainsMono', monospace;
    padding: 0.75rem 1rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  .artlwrimg {
    border-radius: 50%;
    width: 80px;
    height: auto;
  }

  .artlwrhref {
    display: inline-block;
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0D0D0D, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0D0D0D, 0.1);
    margin: 2rem 0;
  }
}
</style>